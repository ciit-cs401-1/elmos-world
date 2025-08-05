<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        try {
            $query = User::with(['roles:id,role_name']);

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Apply role filter
            if ($request->filled('role') && !empty($request->get('role'))) {
                $roleId = $request->get('role');
                // Use whereHas for more reliable filtering
                $query->whereHas('roles', function ($q) use ($roleId) {
                    $q->where('roles.id', $roleId);
                });
            }

            // Apply status filter if provided
            if ($request->filled('status')) {
                $status = $request->get('status');
                $query->where('status', $status);
            }

            // Apply sorting - handle the field|direction format from the form
            $sort = $request->get('sort', 'registration_date|desc');
            $sortParts = explode('|', $sort);
            $sortBy = $sortParts[0] ?? 'registration_date';
            $sortOrder = $sortParts[1] ?? 'desc';
            $query->orderBy($sortBy, $sortOrder);

            // Apply pagination consistently
            $users = $query->paginate(10)->withQueryString();

            // Get roles for filter dropdown
            $roles = Role::select('id', 'role_name')->orderBy('role_name')->get();

            // For debugging - log simple message
            if ($request->filled('role') && !empty($request->get('role'))) {
                Log::info('Role filter applied: ' . $request->get('role'));
            }

            return view('dashboard.users', compact('users', 'roles'));
        } catch (\Exception $e) {
            Log::error('Dashboard users error: ' . $e->getMessage());
            return view('dashboard.users', ['error' => 'An error occurred while loading users: ' . $e->getMessage()]);
        }
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request) // almost the same function as AuthController.store
    {
        try {
            $request->validate([
                'name' => 'required|string|max:30',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
                'roles' => ['required', 'array', 'min:1'],
                'roles.*' => ['required', 'exists:roles,id']
            ]);

            $user = DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'registration_date' => now(),
                ]);

                // Only allow Admin (A) and Contributor (C) roles
                $allowedRoles = Role::whereIn('role_name', ['A', 'C'])
                    ->whereIn('id', $request->roles)
                    ->pluck('id');

                // Attach selected roles
                $user->roles()->attach($allowedRoles);

                return $user;
            });

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User created successfully',
                    'user' => $user->load('roles')
                ]);
            }

            return redirect()->route('dashboard.users')
                ->with('success', 'User created successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            Log::error('Create user error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create user. Please try again.'
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Failed to create user. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $getUser = User::findOrFail($user);
        return view('dashboard.user.account', ['user' => $getUser]);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', Rules\Password::defaults()],
            'confirm_password' => ['required_with:password', 'same:password'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        $posts = Post::where('user_id', $user->id)->get();

        foreach ($posts as $post) {
            $post->comments()->delete();
            $post->media()->delete();
            $post->tags()->detach();
            $post->categories()->detach();
            $post->delete();
        }

        $user->comments()->delete();
        $user->roles()->detach();
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    /**
     * Update a user's role
     */
    public function updateUserRole(Request $request, User $user): RedirectResponse
    {
        try {
            $request->validate([
                'role_id' => 'required|exists:roles,id',
            ]);

            // Clear existing roles and assign the new role
            $user->roles()->sync([$request->role_id]);

            return redirect()->back()->with('success', "Role updated successfully for {$user->name}.");
        } catch (\Exception $e) {
            Log::error('Update user role error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update user role. Please try again.');
        }
    }
}
