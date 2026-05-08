<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%'.$request->search.'%')
                    ->orWhere('last_name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load('roles', 'addresses', 'orders');

        $stats = [
            'total_orders' => $user->orders()->count(),
            'total_spent' => $user->orders()->sum('grand_total'),
            'pending_orders' => $user->orders()->where('status', 'pending')->count(),
            'completed_orders' => $user->orders()->where('status', 'delivered')->count(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return back()->withErrors(['error' => __('Cannot delete admin user.')]);
        }

        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => __('Cannot delete your own account.')]);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('status', __('User deleted successfully.'));
    }

    public function restrict(User $user): RedirectResponse
    {
        if ($user->isAdmin() || $user->id === auth()->id()) {
            return back()->withErrors(['error' => __('Cannot restrict this user.')]);
        }

        // Restriction is currently implemented as a deletion. A dedicated
        // is_active flag should replace this in a future migration.
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('status', __('User deleted successfully.'));
    }

    public function deleteAll(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
            'confirm' => ['required', 'string', 'in:DELETE'],
        ]);

        $deletedCount = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->where('id', '!=', auth()->id())->count();

        User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->where('id', '!=', auth()->id())->delete();

        return redirect()->route('admin.users.index')
            ->with('status', __('Deleted :count users successfully.', ['count' => $deletedCount]));
    }
}
