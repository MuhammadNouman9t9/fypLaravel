<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only([
            'search',
            'category',
            'brand',
            'min_price',
            'max_price',
            'sort',
            'property_type',
            'property_size',
            'entry_points',
            'exit_points',
            'ai_recommend',
        ]);

        if (filled($filters['category'] ?? null)) {
            $selectedCategory = Category::query()
                ->with('children:id,parent_id')
                ->where('slug', $filters['category'])
                ->first();

            if (! $selectedCategory && is_numeric($filters['category'])) {
                $selectedCategory = Category::query()
                    ->with('children:id,parent_id')
                    ->find($filters['category']);
            }

            if ($selectedCategory) {
                $filters['category_ids'] = $selectedCategory->children
                    ->pluck('id')
                    ->prepend($selectedCategory->id)
                    ->unique()
                    ->values()
                    ->all();
            }
        }

        $sortOption = data_get($filters, 'sort', 'latest');

        $query = Product::query()
            ->with(['media', 'categories', 'inventory', 'specifications']);

        // AI Recommendations based on property profile
        if ($request->boolean('ai_recommend') && (
            filled($filters['property_type']) ||
            filled($filters['property_size']) ||
            filled($filters['entry_points']) ||
            filled($filters['exit_points'])
        )) {
            $query = $this->applyAIRecommendations($query, $filters);
        } else {
            $query->active()->filter($filters);
        }

        $products = $query
            ->when($sortOption === 'price_asc', fn ($q) => $q->orderBy('price'))
            ->when($sortOption === 'price_desc', fn ($q) => $q->orderByDesc('price'))
            ->when($sortOption === 'top_rated', fn ($q) => $q->orderByDesc('rating_average'))
            ->when($sortOption === 'quality', fn ($q) => $q->orderByDesc('rating_average')->orderByDesc('reviews_count'))
            ->when(! in_array($sortOption, ['price_asc', 'price_desc', 'top_rated', 'quality'], true), fn ($q) => $q->orderByDesc('created_at'))
            ->paginate(12)
            ->withQueryString();

        $brands = Product::query()
            ->whereNotNull('brand')
            ->where('brand', '<>', '')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        $categories = Category::query()
            ->active()
            ->with(['children' => fn ($query) => $query->active()->orderBy('sort_order')])
            ->roots()
            ->orderBy('sort_order')
            ->get();

        // Get AI recommendations summary
        $aiRecommendations = $this->getAIRecommendationsSummary($filters);

        return view('catalog.index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'activeFilters' => $filters,
            'aiRecommendations' => $aiRecommendations,
        ]);
    }

    protected function applyAIRecommendations($query, array $filters)
    {
        $query->active();

        $propertyType = $filters['property_type'] ?? null;
        $propertySize = $filters['property_size'] ?? null;
        $entryPoints = (int) ($filters['entry_points'] ?? 0);
        $exitPoints = (int) ($filters['exit_points'] ?? 0);

        // AI Logic: Recommend products based on property profile
        if ($propertyType === 'apartment' || $propertyType === 'condo') {
            // Smaller properties need compact solutions
            $query->where(function ($q) {
                $q->where('name', 'like', '%Indoor%')
                    ->orWhere('name', 'like', '%Compact%')
                    ->orWhere('name', 'like', '%Smart Lock%')
                    ->orWhere('name', 'like', '%Doorbell%');
            });
        } elseif ($propertyType === 'house' || $propertyType === 'villa') {
            // Larger properties need comprehensive systems
            $query->where(function ($q) {
                $q->where('name', 'like', '%Outdoor%')
                    ->orWhere('name', 'like', '%Alarm%')
                    ->orWhere('name', 'like', '%Hub%')
                    ->orWhere('name', 'like', '%System%');
            });
        }

        // Entry/Exit points logic
        if ($entryPoints > 2 || $exitPoints > 2) {
            // Multiple entry points need more cameras and sensors
            $query->where(function ($q) {
                $q->where('name', 'like', '%Camera%')
                    ->orWhere('name', 'like', '%Sensor%')
                    ->orWhere('name', 'like', '%Detector%');
            });
        }

        // Property size logic
        if ($propertySize === 'large' || $propertySize === 'very_large') {
            // Large properties need PTZ cameras and multiple sensors
            $query->where(function ($q) {
                $q->where('name', 'like', '%PTZ%')
                    ->orWhere('name', 'like', '%Hub%')
                    ->orWhere('name', 'like', '%System%');
            });
        }

        // Prioritize high-rated products for AI recommendations
        $query->where('rating_average', '>=', 4.0)
            ->orderByDesc('rating_average')
            ->orderByDesc('reviews_count');

        return $query;
    }

    protected function getAIRecommendationsSummary(array $filters): array
    {
        if (! filled($filters['property_type'] ?? null)) {
            return [];
        }

        $recommendations = [];

        $propertyType = $filters['property_type'] ?? null;
        $propertySize = $filters['property_size'] ?? null;
        $entryPoints = (int) ($filters['entry_points'] ?? 0);
        $exitPoints = (int) ($filters['exit_points'] ?? 0);

        // Generate AI recommendations text
        if ($propertyType === 'apartment' || $propertyType === 'condo') {
            $recommendations[] = 'Recommended: Indoor cameras, smart locks, and doorbell cameras for compact spaces.';
        } elseif ($propertyType === 'house' || $propertyType === 'villa') {
            $recommendations[] = 'Recommended: Outdoor cameras, alarm systems, and security hubs for comprehensive coverage.';
        }

        if ($entryPoints > 2) {
            $recommendations[] = "With {$entryPoints} entry points, consider multiple cameras and motion sensors.";
        }

        if ($propertySize === 'large' || $propertySize === 'very_large') {
            $recommendations[] = 'Large property detected: PTZ cameras and automation hubs recommended for full coverage.';
        }

        return $recommendations;
    }

    public function show(Product $product): View
    {
        $product->load([
            'media',
            'specifications',
            'primaryCategory',
            'categories',
            'inventory',
        ]);

        $relatedProducts = Product::query()
            ->active()
            ->whereKeyNot($product->getKey())
            ->whereHas('categories', fn ($query) => $query->whereIn('categories.id', $product->categories->pluck('id')))
            ->with(['media'])
            ->limit(8)
            ->get();

        return view('catalog.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
