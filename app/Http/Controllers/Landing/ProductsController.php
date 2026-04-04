<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
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
                $category = \Illuminate\Support\Facades\Cache::remember(
                    "category_slug_{$request->category}",
                    now()->addHours(24),
                    fn () => Category::with('children')->where('slug', $request->category)->first()
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

        return view('landing.products', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'filters' => $request->only(['company', 'category', 'max_quantity']),
            'aiFilters' => $request->only(['property_type', 'property_size', 'budget', 'entry_points', 'exit_points', 'ai_recommend']),
            'usingAI' => $useAIRecommendation,
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
        $recommendations = \Illuminate\Support\Facades\Cache::remember(
            $cacheKey,
            now()->addMinutes(30),
            fn () => $this->recommendationService->getRecommendations(
                auth()->user(),
                ['type' => 'product_detail', 'product_id' => $product->id]
            )->take(4)
        );

        return view('landing.product-show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'recommendations' => $recommendations,
        ]);
    }
}
