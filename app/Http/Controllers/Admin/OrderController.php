<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'shipments']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%'.$request->search.'%')
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('email', 'like', '%'.$request->search.'%')
                            ->orWhere('first_name', 'like', '%'.$request->search.'%')
                            ->orWhere('last_name', 'like', '%'.$request->search.'%');
                    })
                    ->orWhereHas('shipments', function ($shipmentQuery) use ($request) {
                        $shipmentQuery->where('tracking_number', 'like', '%'.$request->search.'%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->latest('created_at')->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load('user', 'items.product', 'shipments');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,processing,shipped,delivered,cancelled'],
            'fulfillment_status' => ['nullable', 'string', 'in:unfulfilled,fulfilled,partially_fulfilled'],
            'payment_status' => ['nullable', 'string', 'in:unpaid,paid,refunded,partially_refunded'],
        ]);

        $order->update($validated);

        if ($validated['status'] === 'delivered') {
            $order->update(['fulfilled_at' => now()]);
        }

        if ($validated['status'] === 'cancelled') {
            $order->update(['cancelled_at' => now()]);
        }

        return back()->with('status', __('Order status updated successfully.'));
    }

    public function confirm(Order $order): RedirectResponse
    {
        $order->update([
            'status' => 'processing',
            'placed_at' => $order->placed_at ?? now(),
        ]);

        // Create shipment with tracking number when order is confirmed
        if (! $order->shipments()->exists()) {
            Shipment::create([
                'order_id' => $order->id,
                'tracking_number' => 'TRK-'.strtoupper(\Illuminate\Support\Str::random(12)),
                'carrier' => 'SafeNest Express',
                'service_level' => 'Standard',
                'status' => 'pending',
                'expected_delivery_at' => now()->addDays(5),
            ]);
        }

        return back()->with('status', __('Order confirmed successfully. Tracking ID generated.'));
    }
}
