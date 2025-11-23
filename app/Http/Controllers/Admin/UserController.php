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
        $user->load('roles', 'addresses');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return back()->withErrors(['error' => __('Cannot delete admin user.')]);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('status', __('User deleted successfully.'));
    }

    public function restrict(User $user): RedirectResponse
    {
        // Add restriction logic here (e.g., add a 'is_restricted' column)
        // For now, we'll just delete the user
        return $this->destroy($user);
    }

    public function deleteAll(Request $request): RedirectResponse
    {
        // Delete all users except admins
        $deletedCount = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->count();

        User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->delete();

        return redirect()->route('admin.users.index')
            ->with('status', __('Deleted :count users successfully.', ['count' => $deletedCount]));
    }
}
