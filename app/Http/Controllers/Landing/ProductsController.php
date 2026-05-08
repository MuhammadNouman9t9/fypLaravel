<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FraudAlert;
use App\Models\Product;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProductsController extends Controller
{
    public function __construct(
        private RecommendationService $recommendationService
    ) {}

    public function __invoke(Request $request): View
    {
        // Check if AI recommendation is requested
        $useAIRecommendation = $request->boolean('ai_recommend') && (
            $request->filled('property_type') ||
            $request->filled('budget')
        );

        if ($useAIRecommendation) {
            // Use AI recommendations
            $context = [
                'type' => 'product_browse',
                'property_type' => $request->property_type,
                'property_size' => $request->property_size,
                'budget' => $request->budget,
                'entry_points' => $request->entry_points,
                'exit_points' => $request->exit_points,
            ];

            $recommendedProducts = $this->recommendationService->getRecommendations(
                auth()->user(),
                $context
            );

            // Convert to paginated collection
            $products = new \Illuminate\Pagination\LengthAwarePaginator(
                $recommendedProducts,
                $recommendedProducts->count(),
                6,
                $request->get('page', 1),
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            // Use regular filtering - optimized queries
            $query = Product::query()
                ->active()
                ->with(['categories', 'inventory', 'media'])
                ->withCount('categories');

            // Filter by company name (brand) - using index
            if ($request->filled('company') && $request->company !== '') {
                $query->where('brand', $request->company);
            }

            // Filter by category - optimized with join instead of whereHas
            if ($request->filled('category') && $request->category !== '') {
                $categorySlug = (string) $request->category;
                // Hash the slug into the cache key so request input can't pollute the cache.
                $cacheKey = 'category_slug_'.md5($categorySlug);
                $category = \Illuminate\Support\Facades\Cache::remember(
                    $cacheKey,
                    now()->addHours(24),
                    fn () => Category::with('children')->where('slug', $categorySlug)->first()
                );

                if ($category) {
                    $categoryIds = [$category->id];
                    // Include child categories if it's a parent - use eager loaded relationship
                    if ($category->relationLoaded('children') && $category->children->isNotEmpty()) {
                        $categoryIds = array_merge($categoryIds, $category->children->pluck('id')->toArray());
                    } elseif (! $category->relationLoaded('children')) {
                        $childIds = $category->children()->pluck('id')->toArray();
                        if (! empty($childIds)) {
                            $categoryIds = array_merge($categoryIds, $childIds);
                        }
                    }

                    // Use join instead of whereHas for better performance
                    $query->whereHas('categories', function ($q) use ($categoryIds): void {
                        $q->whereIn('categories.id', $categoryIds);
                    });
                }
            }

            // Filter by max quantity - use join for better performance
            if ($request->filled('max_quantity') && $request->max_quantity !== '' && is_numeric($request->max_quantity)) {
                $query->whereHas('inventory', function ($q) use ($request): void {
                    $q->where('quantity_on_hand', '<=', (int) $request->max_quantity);
                });
            }

            $products = $query->paginate(6)->withQueryString();
        }

        // Cache categories and brands - they don't change frequently
        $categories = \Illuminate\Support\Facades\Cache::remember(
            'categories_parent_active',
            now()->addHours(12),
            fn () => Category::whereNull('parent_id')->active()->orderBy('name')->get()
        );

        $brands = \Illuminate\Support\Facades\Cache::remember(
            'products_brands_list',
            now()->addHours(6),
            fn () => Product::active()
                ->whereNotNull('brand')
                ->select('brand')
                ->distinct()
                ->orderBy('brand')
                ->pluck('brand')
                ->values()
        );

        $quickSafetyScore = null;
        if ($request->boolean('ai_score') && $request->filled('property_type')) {
            $quickSafetyScore = $this->calculateQuickSafetyScore($request);
        }

        $fraudInsights = [
            'enabled' => (bool) config('safenest.ai.fraud_detection.enabled'),
            'open_alerts' => 0,
            'high_risk_alerts' => 0,
            'latest_alert_level' => null,
        ];

        if (auth()->check()) {
            $alertsQuery = FraudAlert::query()->where('user_id', auth()->id());

            $fraudInsights['open_alerts'] = (clone $alertsQuery)->where('status', 'open')->count();
            $fraudInsights['high_risk_alerts'] = (clone $alertsQuery)->whereIn('risk_level', ['high', 'critical'])->count();
            $fraudInsights['latest_alert_level'] = (clone $alertsQuery)->latest('detected_at')->value('risk_level');
        }

        return view('landing.products', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'filters' => $request->only(['company', 'category', 'max_quantity']),
            'aiFilters' => $request->only([
                'property_type',
                'property_size',
                'budget',
                'entry_points',
                'exit_points',
                'neighborhood_profile',
                'occupancy_pattern',
                'has_security_system',
                'previous_incidents',
                'ai_recommend',
                'ai_score',
            ]),
            'usingAI' => $useAIRecommendation,
            'quickSafetyScore' => $quickSafetyScore,
            'fraudInsights' => $fraudInsights,
        ]);
    }

    public function show(Product $product): View
    {
        // Eager load all relationships at once
        $product->load(['categories', 'media', 'specifications', 'inventory']);

        // Get category IDs from already loaded relationship
        $categoryIds = $product->categories->pluck('id')->toArray();

        // Get related products from same category - optimized query
        $relatedProducts = collect();
        if (! empty($categoryIds)) {
            $relatedProducts = Product::query()
                ->where('id', '!=', $product->id)
                ->active()
                ->whereHas('categories', function ($query) use ($categoryIds): void {
                    $query->whereIn('categories.id', $categoryIds);
                })
                ->with(['media', 'categories'])
                ->limit(4)
                ->get();
        }

        // Get AI recommendations if available - cache the result
        $cacheKey = 'product_recommendations_'.($product->id ?? 'guest').'_'.$product->id;
        $recommendations = collect();
        try {
            $recommendations = \Illuminate\Support\Facades\Cache::remember(
                $cacheKey,
                now()->addMinutes(30),
                fn () => $this->recommendationService->getRecommendations(
                    auth()->user(),
                    ['type' => 'product_detail', 'product_id' => $product->id]
                )->take(4)
            );
        } catch (\Throwable $e) {
            Log::warning('Product detail recommendations unavailable', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);
        }

        if (! $recommendations instanceof Collection) {
            $recommendations = collect($recommendations)->take(4);
        }

        return view('landing.product-show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'recommendations' => $recommendations,
        ]);
    }

    protected function calculateQuickSafetyScore(Request $request): array
    {
        $propertySize = max(100, (int) $request->integer('property_size', 1000));
        $entryPoints = max(1, (int) $request->integer('entry_points', 2));
        $exitPoints = max(1, (int) $request->integer('exit_points', 1));
        $hasSecuritySystem = $request->boolean('has_security_system');
        $previousIncidents = $request->boolean('previous_incidents');

        $score = 50;

        $score += match ($request->input('property_type')) {
            'apartment', 'condo' => 10,
            'house', 'villa' => 5,
            'townhouse' => 8,
            'commercial' => 3,
            default => 0,
        };

        $score += match (true) {
            $propertySize < 500 => 5,
            $propertySize < 1500 => 0,
            $propertySize < 3000 => -5,
            default => -10,
        };

        $score += match ($request->input('occupancy_pattern')) {
            'always_occupied' => 15,
            'mostly_occupied' => 10,
            'partially_occupied' => 5,
            'rarely_occupied' => -10,
            'vacant' => -20,
            default => 0,
        };

        $score += match ($request->input('neighborhood_profile')) {
            'very_safe' => 15,
            'safe' => 10,
            'moderate' => 0,
            'risky' => -15,
            'high_crime' => -25,
            default => 0,
        };

        $totalPoints = $entryPoints + $exitPoints;
        $score -= match (true) {
            $totalPoints <= 2 => 0,
            $totalPoints <= 4 => 5,
            $totalPoints <= 6 => 10,
            default => 15,
        };

        if ($hasSecuritySystem) {
            $score += 15;
        }

        if ($previousIncidents) {
            $score -= 20;
        }

        $score = max(0, min(100, $score));

        $riskLevel = match (true) {
            $score >= 80 => 'low',
            $score >= 60 => 'moderate',
            $score >= 40 => 'high',
            default => 'critical',
        };

        return [
            'score' => $score,
            'risk_level' => $riskLevel,
        ];
    }
}
