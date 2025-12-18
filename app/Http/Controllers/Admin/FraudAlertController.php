<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FraudAlert;
use App\Services\FraudDetectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FraudAlertController extends Controller
{
    public function __construct(
        private FraudDetectionService $fraudDetectionService
    ) {}

    public function index(Request $request): View
    {
        $query = FraudAlert::query()
            ->with(['order', 'payment', 'user', 'resolver'])
            ->orderByDesc('detected_at');

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('risk_level')) {
            $query->where('risk_level', $request->get('risk_level'));
        }

        $alerts = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => FraudAlert::count(),
            'open' => FraudAlert::where('status', 'open')->count(),
            'resolved' => FraudAlert::where('status', 'resolved')->count(),
            'high_risk' => FraudAlert::where('risk_level', 'high')->orWhere('risk_level', 'critical')->count(),
        ];

        return view('admin.fraud-alerts.index', [
            'alerts' => $alerts,
            'stats' => $stats,
        ]);
    }

    public function show(FraudAlert $fraudAlert): View
    {
        $fraudAlert->load(['order.items.product', 'payment', 'user', 'resolver']);

        return view('admin.fraud-alerts.show', [
            'alert' => $fraudAlert,
        ]);
    }

    public function resolve(Request $request, FraudAlert $fraudAlert): RedirectResponse
    {
        $validated = $request->validate([
            'resolution' => ['required', 'string', 'max:1000'],
        ]);

        $this->fraudDetectionService->resolveAlert(
            $fraudAlert,
            auth()->id(),
            $validated['resolution']
        );

        return redirect()
            ->route('admin.fraud-alerts.show', $fraudAlert)
            ->with('status', __('Fraud alert resolved successfully.'));
    }
}
