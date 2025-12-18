<?php

namespace App\Http\Controllers;

use App\Services\RecommendationService;
use App\Services\RiskAnalyzerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiskAnalyzerController extends Controller
{
    public function __construct(
        private RiskAnalyzerService $riskAnalyzerService,
        private RecommendationService $recommendationService
    ) {}

    public function index(): View
    {
        return view('risk-analyzer.index');
    }

    public function analyze(Request $request): RedirectResponse|View
    {
        $validated = $request->validate([
            'property_type' => ['required', 'string', 'in:apartment,condo,house,villa,townhouse,commercial'],
            'property_size' => ['required', 'integer', 'min:100', 'max:10000'],
            'occupancy_pattern' => ['required', 'string', 'in:always_occupied,mostly_occupied,partially_occupied,rarely_occupied,vacant'],
            'neighborhood_profile' => ['required', 'string', 'in:very_safe,safe,moderate,risky,high_crime'],
            'entry_points' => ['required', 'integer', 'min:1', 'max:20'],
            'exit_points' => ['required', 'integer', 'min:1', 'max:20'],
            'has_security_system' => ['nullable', 'boolean'],
            'previous_incidents' => ['nullable', 'boolean'],
        ]);

        $assessment = $this->riskAnalyzerService->analyze($validated);

        // Get product recommendations based on assessment
        $recommendations = $this->recommendationService->getRecommendations(
            auth()->user(),
            array_merge($validated, ['type' => 'risk_analysis'])
        );

        return view('risk-analyzer.result', [
            'assessment' => $assessment,
            'recommendations' => $recommendations,
        ]);
    }

    public function show(string $uuid): View
    {
        $assessment = \App\Models\RiskAssessment::where('uuid', $uuid)
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhereNull('user_id');
            })
            ->firstOrFail();

        $recommendations = collect();
        if ($assessment->recommendations) {
            $productIds = collect($assessment->recommendations)
                ->pluck('products')
                ->flatten()
                ->unique()
                ->toArray();

            if (! empty($productIds)) {
                $recommendations = \App\Models\Product::query()
                    ->whereIn('id', $productIds)
                    ->active()
                    ->with(['media', 'categories'])
                    ->get();
            }
        }

        return view('risk-analyzer.show', [
            'assessment' => $assessment,
            'recommendations' => $recommendations,
        ]);
    }
}
