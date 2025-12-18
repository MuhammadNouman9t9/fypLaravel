<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function __construct(
        private AnalyticsService $analyticsService
    ) {}

    public function index(Request $request): View
    {
        $period = $request->get('period', '30');
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : now()->subDays((int) $period);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : now();

        $overview = $this->analyticsService->getSalesOverview($startDate, $endDate);
        $topProducts = $this->analyticsService->getTopSellingProducts(10, $startDate, $endDate);
        $salesTrends = $this->analyticsService->getSalesTrends('daily', (int) $period);
        $revenueByCategory = $this->analyticsService->getRevenueByCategory($startDate, $endDate);
        $customerMetrics = $this->analyticsService->getCustomerMetrics($startDate, $endDate);
        $conversionMetrics = $this->analyticsService->getConversionMetrics($startDate, $endDate);

        return view('admin.analytics.index', [
            'overview' => $overview,
            'topProducts' => $topProducts,
            'salesTrends' => $salesTrends,
            'revenueByCategory' => $revenueByCategory,
            'customerMetrics' => $customerMetrics,
            'conversionMetrics' => $conversionMetrics,
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
