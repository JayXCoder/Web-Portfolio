<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,viewer',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        $user = User::create($validated);

        // Log user creation
        Log::info('User created', [
            'created_by' => auth()->id(),
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'ip' => $request->ip(),
            'timestamp' => now()
        ]);

        return redirect()->to(secure_url(route('admin.users')))
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,viewer',
            'is_active' => 'boolean',
        ]);

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        // Log user update
        Log::info('User updated', [
            'updated_by' => auth()->id(),
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'ip' => $request->ip(),
            'timestamp' => now()
        ]);

        return redirect()->to(secure_url(route('admin.users')))
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deleting the current user
        if ($user->id === auth()->id()) {
            return redirect()->to(secure_url(route('admin.users')))
                ->with('error', 'You cannot delete your own account!');
        }

        // Prevent deleting the last admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->to(secure_url(route('admin.users')))
                ->with('error', 'Cannot delete the last admin user!');
        }

        // Log user deletion
        Log::info('User deleted', [
            'deleted_by' => auth()->id(),
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'timestamp' => now()
        ]);

        $user->delete();

        return redirect()->to(secure_url(route('admin.users')))
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        // Prevent deactivating the current user
        if ($user->id === auth()->id()) {
            return redirect()->to(secure_url(route('admin.users')))
                ->with('error', 'You cannot deactivate your own account!');
        }

        // Prevent deactivating the last admin
        if ($user->role === 'admin' && $user->is_active && User::where('role', 'admin')->active()->count() <= 1) {
            return redirect()->to(secure_url(route('admin.users')))
                ->with('error', 'Cannot deactivate the last admin user!');
        }

        $user->update(['is_active' => !$user->is_active]);

        // Log status change
        \Log::info('User status toggled', [
            'toggled_by' => auth()->id(),
            'user_id' => $user->id,
            'user_email' => $user->email,
            'new_status' => $user->is_active ? 'active' : 'inactive',
            'timestamp' => now()
        ]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->to(secure_url(route('admin.users')))
            ->with('success', "User {$status} successfully!");
    }
}