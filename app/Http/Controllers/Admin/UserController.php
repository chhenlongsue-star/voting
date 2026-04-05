<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // PROTECTION: Sub-Admins cannot edit Full Admins
        if (Auth::user()->role === 'sub_admin' && $user->role === 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'You do not have permission to edit a Full Admin.');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // 1. SECURITY CHECK: Prevent Sub-Admins from modifying Full Admins
        if (Auth::user()->role === 'sub_admin' && $user->role === 'admin') {
            return back()->with('error', 'Sub-Admins cannot modify Full Admin accounts.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,sub_admin,user',
        ]);

        // 2. HIERARCHY CHECK: Prevent Sub-Admins from promoting anyone to Full Admin
        if (Auth::user()->role === 'sub_admin' && $request->role === 'admin') {
            return back()->with('error', 'Only Full Admins can promote users to the Admin role.');
        }

        $user->update($request->only('name', 'role'));

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Prevent Sub-Admins from deleting Admins
        if (Auth::user()->role === 'sub_admin' && $user->role === 'admin') {
            return back()->with('error', 'Sub-Admins cannot delete Full Admins.');
        }

        $user->delete();
        return back()->with('success', 'User deleted from database.');
    }
}