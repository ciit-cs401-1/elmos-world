<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Role;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index(): View
    {
        try {
            // Get dashboard statistics
            $stats = [
                'total_posts' => Post::count(),
                'published_posts' => Post::where('status', 'P')->count(),
                'draft_posts' => Post::where('status', 'D')->count(),
                'total_users' => User::count(),
                'total_subscribers' => User::whereHas('roles', function ($query) {
                    $query->where('role_name', 'subscriber');
                })->count(),
                'total_contributors' => User::whereHas('roles', function ($query) {
                    $query->whereIn('role_name', ['author', 'editor', 'admin']);
                })->count(),
                'total_comments' => Comment::count(),
                'pending_comments' => Comment::where('status', 'pending')->count(),
            ];

            // Get recent posts with author information
            $recentPosts = Post::with(['users:id,name', 'categories:id,category_name'])
                ->select('id', 'title', 'content', 'status', 'created_at', 'updated_at', 'views_count')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Get recent users with roles
            $recentUsers = User::with(['roles:id,role_name'])
                ->select('id', 'name', 'email', 'profile_photo', 'created_at', 'updated_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return view('dashboard.index', compact('stats', 'recentPosts', 'recentUsers'));
        } catch (\Exception $e) {
            Log::error('Dashboard index error: ' . $e->getMessage());

            // Provide default stats in case of error
            $stats = [
                'total_posts' => 0,
                'published_posts' => 0,
                'draft_posts' => 0,
                'total_users' => 0,
                'total_subscribers' => 0,
                'total_contributors' => 0,
                'total_comments' => 0,
                'pending_comments' => 0,
            ];
            $recentPosts = collect();
            $recentUsers = collect();

            return view('dashboard.index', compact('stats', 'recentPosts', 'recentUsers'))
                ->with('error', 'Failed to load dashboard data.');
        }
    }

    /**
     * Show all posts for dashboard management
     */
    public function posts(Request $request): View
    {
        try {
            $query = Post::with(['users:id,name', 'categories:id,category_name']);

            // Apply search filter - only search in title
            if ($request->has('search') && !empty(trim($request->input('search')))) {
                $search = trim($request->input('search'));
                Log::info('Posts search query:', ['search' => $search]);
                
                $query->where('title', 'like', "%{$search}%");
            }

            // Apply status filter
            if ($request->filled('status')) {
                $query->where('status', $request->get('status'));
            }

            // Apply sorting
            $sortBy = $request->get('sort', 'created_at');
            $sortOrder = $request->get('order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $posts = $query->paginate(10)->withQueryString();

            return view('dashboard.posts', compact('posts'));
        } catch (\Exception $e) {
            Log::error('Dashboard posts error: ' . $e->getMessage());
            return view('dashboard.posts')->with('error', 'Failed to load posts data.');
        }
    }

    /**
     * Show all users for dashboard management
     */
    public function users(Request $request): View
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
                $query->whereHas('roles', function($q) use ($roleId) {
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
     * Store a new user
     */
    public function storeUser(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:30',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
                'roles' => ['required', 'array', 'min:1'],
                'roles.*' => ['required', 'exists:roles,id']
            ]);
            
            $user = DB::transaction(function() use ($request) {
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
     * Toggle post status via AJAX
     */
    public function togglePostStatus(Request $request, Post $post): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:P,D,I',
            ]);

            $updateData = ['status' => $request->status];

            if ($request->status === 'P' && !$post->publication_date) {
                $updateData['publication_date'] = now();
            }

            $post->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Post status updated successfully',
                'status' => $post->status,
                'publication_date' => $post->publication_date,
            ]);
        } catch (\Exception $e) {
            Log::error('Toggle post status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update post status'
            ], 500);
        }
    }


    /**
     * Delete a user
     */
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

    /**
     * Delete a user
     */
    public function deleteUser(User $user): RedirectResponse
    {
        try {
            // Prevent deleting current user
            if ($user->id === auth()->id()) {
                return redirect()->back()->with('error', 'You cannot delete your own account.');
            }

            $name = $user->name;

            // Use DB transaction to ensure all operations complete or none do
            DB::transaction(function() use ($user) {
                // Get user's posts first
                $posts = $user->posts()->get();
                
                // Clean up each post's relationships before deletion
                foreach ($posts as $post) {
                    // Delete media associated with this post
                    // First find media related to this post
                    $mediaRecords = DB::table('media')->where('post_id', $post->id)->get();
                    
                    // Delete each media record
                    foreach ($mediaRecords as $media) {
                        DB::table('media')->where('id', $media->id)->delete();
                    }
                    
                    // Detach categories
                    $post->categories()->detach();
                    
                    // Detach tags
                    $post->tags()->detach();
                    
                    // Delete comments on this post
                    $post->comments()->delete();
                    
                    // Now delete the post
                    $post->delete();
                }
                
                // Delete user's comments on other posts
                $user->comments()->delete();
                
                // Detach roles
                $user->roles()->detach();
                
                // Delete the user
                $user->delete();
            });

            return redirect()->back()->with('success', "User '{$name}' has been deleted successfully.");
        } catch (\Exception $e) {
            Log::error('Delete user error: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Failed to delete user. Error: ' . $e->getMessage());
        }
    }

    /**
     * Bulk actions for posts
     */
    public function bulkPostActions(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'action' => 'required|in:delete,publish,draft',
                'post_ids' => 'required|array',
                'post_ids.*' => 'exists:posts,id'
            ]);

            $postIds = $request->post_ids;
            $action = $request->action;
            $count = 0;

            DB::transaction(function () use ($postIds, $action, &$count) {
                switch ($action) {
                    case 'delete':
                        $posts = Post::whereIn('id', $postIds)->get();
                        foreach ($posts as $post) {
                            $post->categories()->detach();
                            $post->tags()->detach();
                            $post->comments()->delete();
                            $post->delete();
                            $count++;
                        }
                        break;

                    case 'publish':
                        $count = Post::whereIn('id', $postIds)->update(['status' => 'P']);
                        break;

                    case 'draft':
                        $count = Post::whereIn('id', $postIds)->update(['status' => 'D']);
                        break;
                }
            });

            $actionText = match ($action) {
                'delete' => 'deleted',
                'publish' => 'published',
                'draft' => 'moved to draft'
            };

            return redirect()->back()->with('success', "{$count} posts have been {$actionText} successfully.");
        } catch (\Exception $e) {
            Log::error('Bulk post actions error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to perform bulk action. Please try again.');
        }
    }

    /**
     * Get dashboard analytics data
     */
    public function analytics(): View
    {
        try {
            // Posts analytics
            $postsAnalytics = [
                'posts_by_month' => Post::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                    ->whereYear('created_at', date('Y'))
                    ->groupBy('month')
                    ->pluck('count', 'month')
                    ->toArray(),

                'posts_by_status' => Post::selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray(),

                'top_posts' => Post::orderBy('views_count', 'desc')
                    ->limit(10)
                    ->get(['id', 'title', 'views_count']),
            ];

            // Users analytics
            $usersAnalytics = [
                'users_by_month' => User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                    ->whereYear('created_at', date('Y'))
                    ->groupBy('month')
                    ->pluck('count', 'month')
                    ->toArray(),

                'users_by_role' => DB::table('user_role')
                    ->join('roles', 'user_role.role_id', '=', 'roles.id')
                    ->selectRaw('roles.role_name, COUNT(*) as count')
                    ->groupBy('roles.role_name')
                    ->pluck('count', 'role_name')
                    ->toArray(),
            ];

            return view('dashboard.analytics', compact('postsAnalytics', 'usersAnalytics'));
        } catch (\Exception $e) {
            Log::error('Dashboard analytics error: ' . $e->getMessage());
            return view('dashboard.analytics')->with('error', 'Failed to load analytics data.');
        }
    }

    /**
     * Get a post
     */
    public function showPost(string $id)
    {
        Log::info("Post.show - user is in show page - targeted post to show ---> $id");

        // Step 1: Retrieve the post or fail
        $post = Post::with('categories')->findOrFail($id);

        // Step 2: Increment view count
        $post->increment('views_count');

        // Step 3: Get other data
        $others = Post::with('categories')->inRandomOrder()->take(3)->get();
        $comments = $post->comments()->with('users')->get();
        $user_id = Auth::id();
        $user_object = User::find($user_id); // returns the User model or null

        // Step 4: Return view
        return view('components.dashboard.posts.show', [
            'post' => $post,
            'comments' => $comments,
            'others' => $others,
            'user_object_of_the_one_looking_at_this_page' => $user_object,
        ]);
    }
}
