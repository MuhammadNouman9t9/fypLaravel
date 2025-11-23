<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\SupportConversation;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('grand_total'),
            'open_support_tickets' => SupportConversation::where('status', 'open')->count(),
            'recent_orders' => Order::with(['user', 'shipments'])->latest('created_at')->limit(5)->get(),
            'recent_users' => User::latest('created_at')->limit(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
