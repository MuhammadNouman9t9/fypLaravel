<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AnalyticsService
{
    public function getSalesOverview(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        // Use aggregate queries instead of loading all records into memory
        $totalRevenue = Order::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        $totalOrders = Order::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->count();

        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Use distinct count instead of loading all and counting
        $totalCustomers = Order::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->distinct('user_id')
            ->count('user_id');

        return [
            'total_revenue' => round($totalRevenue, 2),
            'total_orders' => $totalOrders,
            'average_order_value' => round($averageOrderValue, 2),
            'total_customers' => $totalCustomers,
            'period' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString(),
            ],
        ];
    }

    public function getTopSellingProducts(int $limit = 10, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        return OrderItem::query()
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('payment_status', 'paid');
            })
            ->selectRaw('product_id, SUM(quantity) as total_quantity, SUM(total) as total_revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                $product = Product::find($item->product_id);

                return [
                    'product_id' => $item->product_id,
                    'product_name' => $product?->name ?? 'Unknown',
                    'total_quantity' => $item->total_quantity,
                    'total_revenue' => round($item->total_revenue, 2),
                    'product' => $product,
                ];
            });
    }

    public function getSalesTrends(string $period = 'daily', int $days = 30): array
    {
        $startDate = now()->subDays($days);
        $endDate = now();

        $orders = Order::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->get()
            ->groupBy(function ($order) use ($period) {
                if ($period === 'daily') {
                    return $order->created_at->format('Y-m-d');
                } elseif ($period === 'weekly') {
                    return $order->created_at->format('Y-W');
                } else {
                    return $order->created_at->format('Y-m');
                }
            });

        $trends = [];
        foreach ($orders as $date => $dateOrders) {
            $trends[] = [
                'date' => $date,
                'revenue' => round($dateOrders->sum('grand_total'), 2),
                'orders' => $dateOrders->count(),
                'customers' => $dateOrders->pluck('user_id')->unique()->count(),
            ];
        }

        return $trends;
    }

    public function getRevenueByCategory(?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        return OrderItem::query()
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('payment_status', 'paid');
            })
            ->with(['product.categories'])
            ->get()
            ->groupBy(function ($item) {
                $primaryCategory = $item->product?->categories?->first()?->name ?? 'Uncategorized';

                return $primaryCategory;
            })
            ->map(function ($items, $category) {
                return [
                    'category' => $category,
                    'revenue' => round($items->sum('total'), 2),
                    'quantity' => $items->sum('quantity'),
                    'orders' => $items->pluck('order_id')->unique()->count(),
                ];
            })
            ->sortByDesc('revenue')
            ->values();
    }

    public function getCustomerMetrics(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        $newCustomers = User::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $returningCustomers = Order::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->get()
            ->pluck('user_id')
            ->unique()
            ->count();

        $averageCustomerLifetimeValue = $this->calculateAverageLTV($startDate, $endDate);

        return [
            'new_customers' => $newCustomers,
            'returning_customers' => $returningCustomers,
            'total_customers' => $newCustomers + $returningCustomers,
            'average_ltv' => round($averageCustomerLifetimeValue, 2),
        ];
    }

    protected function calculateAverageLTV(Carbon $startDate, Carbon $endDate): float
    {
        $customers = User::query()
            ->whereHas('orders', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('payment_status', 'paid');
            })
            ->with(['orders' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('payment_status', 'paid');
            }])
            ->get();

        if ($customers->isEmpty()) {
            return 0;
        }

        $totalLTV = $customers->sum(function ($customer) {
            return $customer->orders->sum('grand_total');
        });

        return $totalLTV / $customers->count();
    }

    public function getProductPerformance(int $productId, ?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        $items = OrderItem::query()
            ->where('product_id', $productId)
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('payment_status', 'paid');
            })
            ->get();

        return [
            'product_id' => $productId,
            'total_sold' => $items->sum('quantity'),
            'total_revenue' => round($items->sum('total'), 2),
            'average_price' => round($items->avg('unit_price'), 2),
            'orders_count' => $items->pluck('order_id')->unique()->count(),
        ];
    }

    public function getConversionMetrics(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        $totalVisitors = User::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalOrders = Order::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->count();

        $conversionRate = $totalVisitors > 0 ? ($totalOrders / $totalVisitors) * 100 : 0;

        // Carts are session-based (no Cart model). Use unpaid orders in range as incomplete checkouts.
        $unpaidOrders = Order::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'unpaid')
            ->count();

        $checkoutTotal = $totalOrders + $unpaidOrders;
        $cartAbandonmentRate = $checkoutTotal > 0
            ? round(($unpaidOrders / $checkoutTotal) * 100, 2)
            : 0;

        return [
            'total_visitors' => $totalVisitors,
            'total_orders' => $totalOrders,
            'conversion_rate' => round($conversionRate, 2),
            'abandoned_carts' => $unpaidOrders,
            'cart_abandonment_rate' => $cartAbandonmentRate,
        ];
    }
}
