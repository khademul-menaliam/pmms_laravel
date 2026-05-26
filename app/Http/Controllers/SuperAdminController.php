<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuperAdminController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->where('is_superadmin', false)
            ->withCount(['incomes', 'expenses'])
            ->orderByDesc('last_login_at')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function block(User $user): RedirectResponse
    {
        $user->update(['is_blocked' => true]);

        return back()->with('success', "User {$user->name} has been blocked.");
    }

    public function unblock(User $user): RedirectResponse
    {
        $user->update(['is_blocked' => false]);

        return back()->with('success', "User {$user->name} has been unblocked.");
    }

    public function destroy(User $user): RedirectResponse
    {
        // Deleting user and all their data (cascaded by BelongsToUser logic usually or DB constraints)
        $user->delete();

        return back()->with('success', "User {$user->name} and all their data have been deleted.");
    }

    public function impersonate(User $user, Request $request): RedirectResponse
    {
        $request->session()->put('impersonated_user_id', $user->id);

        return redirect()
            ->route('dashboard')
            ->with('success', "Now viewing as {$user->name}. Editing is disabled.");
    }

    public function stopImpersonating(Request $request): RedirectResponse
    {
        $request->session()->forget('impersonated_user_id');

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Back to Super Admin view.');
    }
}
