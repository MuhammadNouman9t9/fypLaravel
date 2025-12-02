<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->with(['items.product.media', 'shipments', 'payments'])
            ->latest('created_at')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): View
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load([
            'items.product.media',
            'shipments',
            'payments',
        ]);

        return view('orders.show', compact('order'));
    }
}
