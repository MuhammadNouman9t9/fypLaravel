<?php

namespace App\Services;

use App\Models\AIRecommendation;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;

class RecommendationService
{
    public function getRecommendations(?User $user = null, array $context = []): Collection
    {
        if (! config('safenest.ai.recommendation.enabled')) {
            return collect();
        }

        // Check if Python API is configured
        $pythonApiUrl = config('services.safenest.python_api.url');
        if ($pythonApiUrl) {
            return $this->getPythonRecommendations($user, $context);
        }

        // Use PHP-based implementation
        $algorithm = config('safenest.ai.recommendation.model', 'hybrid');

        if ($algorithm === 'hybrid') {
            return $this->getHybridRecommendations($user, $context);
        } elseif ($algorithm === 'collaborative') {
            return $this->getCollaborativeRecommendations($user);
        } else {
            return $this->getContentBasedRecommendations($context);
        }
    }

    protected function getPythonRecommendations(?User $user, array $context): Collection
    {
        $apiUrl = config('services.safenest.python_api.url');
        $apiKey = config('services.safenest.python_api.key');

        if (! $apiUrl || ! $apiKey) {
            \Illuminate\Support\Facades\Log::error('Python API URL or key not configured');

            throw new \Exception('Python API not configured. Please set SAFENEST_PYTHON_API_URL and SAFENEST_PYTHON_API_KEY in .env');
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->timeout(10)->post("{$apiUrl}/recommendations", [
                'user_id' => $user?->id,
                'context' => $context,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $productIds = $data['product_ids'] ?? [];

                if (! empty($productIds)) {
                    $products = Product::query()
                        ->whereIn('id', $productIds)
                        ->active()
                        ->with(['media', 'categories'])
                        ->get();

                    // Save recommendation
                    $this->saveRecommendation($user, $context, $products, 'python_api');

                    return $products;
                }

                throw new \Exception('No product recommendations returned from Python API');
            }

            throw new \Exception('Python API returned error: '.$response->body());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Python recommendation API error: '.$e->getMessage());

            throw $e;
        }
    }

    protected function getHybridRecommendations(?User $user, array $context): Collection
    {
        $collaborative = $user ? $this->getCollaborativeRecommendations($user) : collect();
        $contentBased = $this->getContentBasedRecommendations($context);

        // Merge and deduplicate
        $merged = $collaborative->merge($contentBased)->unique('id');

        // Score and sort
        return $merged->map(function ($product) use ($user, $context) {
            $score = $product->rating_average ?? 0;

            // Boost score if user has purchased similar products
            if ($user) {
                $similarPurchases = $this->getSimilarPurchases($user, $product);
                $score += $similarPurchases * 0.5;
            }

            // Boost score based on context
            if (! empty($context)) {
                $contextScore = $this->getContextScore($product, $context);
                $score += $contextScore;
            }

            $product->recommendation_score = $score;

            return $product;
        })->sortByDesc('recommendation_score')->take(12);
    }

    protected function getCollaborativeRecommendations(?User $user): Collection
    {
        if (! $user) {
            return collect();
        }

        // Get user's past orders
        $userOrders = $user->orders()
            ->where('payment_status', 'paid')
            ->with('items.product')
            ->get();

        if ($userOrders->isEmpty()) {
            return collect();
        }

        // Get products user has purchased
        $purchasedProductIds = $userOrders->flatMap(function ($order) {
            return $order->items->pluck('product_id');
        })->unique()->toArray();

        // Find users with similar purchase patterns
        $similarUsers = $this->findSimilarUsers($user, $purchasedProductIds);

        // Get products those users bought but current user hasn't
        $recommendedProductIds = $this->getProductsFromSimilarUsers($similarUsers, $purchasedProductIds);

        if (empty($recommendedProductIds)) {
            return collect();
        }

        return Product::query()
            ->whereIn('id', $recommendedProductIds)
            ->active()
            ->with(['media', 'categories'])
            ->get();
    }

    protected function getContentBasedRecommendations(array $context): Collection
    {
        $query = Product::query()->active()->with(['media', 'categories']);

        // Property type recommendations
        if (isset($context['property_type'])) {
            $this->applyPropertyTypeFilter($query, $context['property_type']);
        }

        // Property size recommendations
        if (isset($context['property_size'])) {
            $this->applyPropertySizeFilter($query, $context['property_size']);
        }

        // Entry/exit points
        if (isset($context['entry_points']) || isset($context['exit_points'])) {
            $entryPoints = (int) ($context['entry_points'] ?? 0);
            $exitPoints = (int) ($context['exit_points'] ?? 0);
            if ($entryPoints > 2 || $exitPoints > 2) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%Camera%')
                        ->orWhere('name', 'like', '%Sensor%')
                        ->orWhere('name', 'like', '%Detector%');
                });
            }
        }

        // Budget filtering
        if (isset($context['budget'])) {
            $budget = (float) $context['budget'];
            if ($budget > 0) {
                // Filter products within budget range
                // Allow some flexibility (10% above budget)
                $maxPrice = $budget * 1.1;
                $query->where('price', '<=', $maxPrice);

                // Prioritize products closer to budget
                $query->orderByRaw("ABS(price - {$budget}) ASC");
            }
        }

        // Neighborhood risk
        if (isset($context['neighborhood_profile'])) {
            if (in_array($context['neighborhood_profile'], ['risky', 'high_crime'])) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%System%')
                        ->orWhere('name', 'like', '%Alarm%')
                        ->orWhere('name', 'like', '%Monitoring%');
                });
            }
        }

        // Sort by rating and popularity (if no budget specified)
        if (! isset($context['budget']) || (float) ($context['budget'] ?? 0) <= 0) {
            $query->where('rating_average', '>=', 4.0)
                ->orderByDesc('rating_average')
                ->orderByDesc('reviews_count');
        }

        return $query->limit(12)->get();
    }

    protected function applyPropertyTypeFilter($query, string $propertyType): void
    {
        if ($propertyType === 'apartment' || $propertyType === 'condo') {
            $query->where(function ($q) {
                $q->where('name', 'like', '%Indoor%')
                    ->orWhere('name', 'like', '%Compact%')
                    ->orWhere('name', 'like', '%Smart Lock%')
                    ->orWhere('name', 'like', '%Doorbell%');
            });
        } elseif ($propertyType === 'house' || $propertyType === 'villa') {
            $query->where(function ($q) {
                $q->where('name', 'like', '%Outdoor%')
                    ->orWhere('name', 'like', '%Alarm%')
                    ->orWhere('name', 'like', '%Hub%')
                    ->orWhere('name', 'like', '%System%');
            });
        }
    }

    protected function applyPropertySizeFilter($query, $propertySize): void
    {
        $size = is_numeric($propertySize) ? (int) $propertySize : 0;

        if ($size > 2000) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%PTZ%')
                    ->orWhere('name', 'like', '%Hub%')
                    ->orWhere('name', 'like', '%System%');
            });
        }
    }

    protected function findSimilarUsers(User $user, array $purchasedProductIds): Collection
    {
        // Find users who purchased at least 2 of the same products
        return User::query()
            ->where('id', '!=', $user->id)
            ->whereHas('orders.items', function ($query) use ($purchasedProductIds) {
                $query->whereIn('product_id', $purchasedProductIds);
            }, '>=', 2)
            ->limit(10)
            ->get();
    }

    protected function getProductsFromSimilarUsers(Collection $similarUsers, array $excludeProductIds): array
    {
        $productIds = [];

        foreach ($similarUsers as $similarUser) {
            $orders = $similarUser->orders()
                ->where('payment_status', 'paid')
                ->with('items')
                ->get();

            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    if (! in_array($item->product_id, $excludeProductIds)) {
                        $productIds[] = $item->product_id;
                    }
                }
            }
        }

        // Count frequency and return top products
        $frequency = array_count_values($productIds);
        arsort($frequency);

        return array_slice(array_keys($frequency), 0, 12);
    }

    protected function getSimilarPurchases(User $user, Product $product): float
    {
        $userOrders = $user->orders()
            ->where('payment_status', 'paid')
            ->with('items.product.categories')
            ->get();

        $similarity = 0;

        foreach ($userOrders as $order) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    // Check category similarity
                    $productCategories = $product->categories->pluck('id')->toArray();
                    $purchasedCategories = $item->product->categories->pluck('id')->toArray();

                    $commonCategories = count(array_intersect($productCategories, $purchasedCategories));
                    if ($commonCategories > 0) {
                        $similarity += 0.2 * $commonCategories;
                    }
                }
            }
        }

        return min($similarity, 2.0); // Cap at 2.0
    }

    protected function getContextScore(Product $product, array $context): float
    {
        $score = 0;

        // Property type match
        if (isset($context['property_type'])) {
            $propertyType = $context['property_type'];
            $name = strtolower($product->name);

            if (($propertyType === 'apartment' || $propertyType === 'condo') &&
                (str_contains($name, 'indoor') || str_contains($name, 'compact') || str_contains($name, 'doorbell'))) {
                $score += 0.5;
            } elseif (($propertyType === 'house' || $propertyType === 'villa') &&
                (str_contains($name, 'outdoor') || str_contains($name, 'system') || str_contains($name, 'alarm'))) {
                $score += 0.5;
            }
        }

        // Property size match
        if (isset($context['property_size'])) {
            $size = (int) ($context['property_size'] ?? 0);
            $name = strtolower($product->name);

            if ($size > 2000 && (str_contains($name, 'ptz') || str_contains($name, 'hub') || str_contains($name, 'system'))) {
                $score += 0.3;
            }
        }

        // Budget match - prefer products closer to budget
        if (isset($context['budget']) && $context['budget'] > 0) {
            $budget = (float) $context['budget'];
            $priceDiff = abs($product->price - $budget);
            $budgetRange = $budget * 0.2; // 20% of budget as acceptable range

            if ($priceDiff <= $budgetRange) {
                // Product is within acceptable budget range
                $score += 0.4;
            } elseif ($product->price <= $budget) {
                // Product is under budget (good)
                $score += 0.2;
            }
        }

        return $score;
    }

    public function saveRecommendation(?User $user, array $context, Collection $products, string $algorithm = 'hybrid'): AIRecommendation
    {
        return AIRecommendation::create([
            'user_id' => $user?->id,
            'session_id' => session()->getId(),
            'context_type' => $context['type'] ?? 'general',
            'algorithm' => $algorithm,
            'input_payload' => $context,
            'products' => $products->pluck('id')->toArray(),
            'confidence_score' => $this->calculateConfidenceScore($products),
            'requested_at' => now(),
            'responded_at' => now(),
        ]);
    }

    protected function calculateConfidenceScore(Collection $products): float
    {
        if ($products->isEmpty()) {
            return 0;
        }

        $avgRating = $products->avg('rating_average') ?? 0;
        $avgReviews = $products->avg('reviews_count') ?? 0;

        // Normalize to 0-100
        $ratingScore = ($avgRating / 5) * 50;
        $reviewScore = min(($avgReviews / 100) * 50, 50);

        return round($ratingScore + $reviewScore, 2);
    }
}
