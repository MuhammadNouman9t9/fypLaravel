<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::query()
            ->where('user_id', auth()->id())
            ->with(['items.product', 'shipments', 'payments'])
            ->latest('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load([
            'items.product.media',
            'shipments',
            'payments',
            'user',
        ]);

        return view('orders.show', compact('order'));
    }
}
