<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use App\Notifications\OrderStatusUpdatedNotification;
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

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $orders = $query->latest('created_at')->paginate(15)->withQueryString();

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

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        $order->update($validated);

        if ($validated['status'] === 'delivered') {
            $order->update(['fulfilled_at' => now()]);
        }

        if ($validated['status'] === 'cancelled') {
            $order->update(['cancelled_at' => now()]);
        }

        // Send email notification to user if status changed
        if ($oldStatus !== $newStatus && $order->user) {
            try {
                $order->load('shipments');
                $order->user->notify(new OrderStatusUpdatedNotification($order, $oldStatus, $newStatus));
            } catch (\Exception $e) {
                \Log::error('Failed to send order status email', [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return back()->with('status', __('Order status updated successfully. Email notification sent to customer.'));
    }

    public function confirm(Order $order): RedirectResponse
    {
        $oldStatus = $order->status;

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

        // Send email notification to user
        if ($oldStatus !== 'processing' && $order->user) {
            try {
                $order->load('shipments');
                $order->user->notify(new OrderStatusUpdatedNotification($order, $oldStatus, 'processing'));
            } catch (\Exception $e) {
                \Log::error('Failed to send order confirmation email', [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return back()->with('status', __('Order confirmed successfully. Tracking ID generated. Email notification sent to customer.'));
    }
}
