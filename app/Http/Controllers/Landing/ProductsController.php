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
            // Use regular filtering
            $query = Product::query()
                ->active()
                ->with(['categories', 'inventory', 'media'])
                ->withCount('categories');

            // Filter by company name (brand)
            if ($request->filled('company') && $request->company !== '') {
                $query->where('brand', $request->company);
            }

            // Filter by category
            if ($request->filled('category') && $request->category !== '') {
                $category = Category::where('slug', $request->category)->first();
                if ($category) {
                    $categoryIds = [$category->id];
                    // Include child categories if it's a parent
                    if ($category->children()->exists()) {
                        $categoryIds = array_merge($categoryIds, $category->children()->pluck('id')->toArray());
                    }
                    $query->whereHas('categories', function ($q) use ($categoryIds) {
                        $q->whereIn('categories.id', $categoryIds);
                    });
                }
            }

            // Filter by max quantity
            if ($request->filled('max_quantity') && $request->max_quantity !== '' && is_numeric($request->max_quantity)) {
                $query->whereHas('inventory', function ($q) use ($request) {
                    $q->where('quantity_on_hand', '<=', (int) $request->max_quantity);
                });
            }

            $products = $query->paginate(6)->withQueryString();
        }

        $categories = Category::whereNull('parent_id')->active()->orderBy('name')->get();
        $brands = Product::active()
            ->whereNotNull('brand')
            ->distinct()
            ->pluck('brand')
            ->sort()
            ->values();

        return view('landing.products', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'filters' => $request->only(['company', 'category', 'max_quantity']),
            'aiFilters' => $request->only(['property_type', 'property_size', 'budget', 'entry_points', 'exit_points', 'ai_recommend']),
            'usingAI' => $useAIRecommendation,
        ]);
    }
}
