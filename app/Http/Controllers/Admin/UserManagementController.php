<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\master_lingkungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:superadmin');
    }

    public function index()
    {
        $users = User::with('roles')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $lingkungans = master_lingkungan::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        $userLingkungans = $user->lingkungans->pluck('id')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'lingkungans', 'userRoles', 'userLingkungans'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'lingkungans' => 'nullable|array',
            'lingkungans.*' => 'exists:master_lingkungans,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Sync roles
        $user->roles()->sync($request->roles);

        // Sync lingkungans (only for lingkungan_admin)
        $hasLingkunganRole = Role::whereIn('id', $request->roles)
            ->where('slug', 'lingkungan_admin')
            ->exists();

        if ($hasLingkunganRole && $request->has('lingkungans')) {
            $user->lingkungans()->sync($request->lingkungans);
        } else {
            $user->lingkungans()->detach();
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()
            ->with('success', 'Password reset successfully.');
    }
}
