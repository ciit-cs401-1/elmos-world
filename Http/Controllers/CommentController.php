<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        Log::info("Comment.store - Comment Store function START");
        Log::info("Comment.store - \$request values", $request->all());

        // Step 1: Validate request data
        $validated_request_items = $request->validate([
            'post_id' => 'required|integer|exists:posts,id',
            'comment_context' => 'required|string|max:1000',
        ]);

        // Step 2: Check if user is (UnAuth Viewer) or (Auth Viewer) since post is public even when logged out. 
        $user = Auth::user();
        if (!$user) {
            Log::warning("Comment.store - Unauthorized attempt to post comment.");
            return redirect()->back()->withErrors('You must be logged in to comment.');
        }

        // Step 3: Create a new Comment instance
        $comment = new Comment();
        $comment->comment_context = $validated_request_items["comment_context"];
        $comment->reviewer_name = $user->name;
        $comment->reviewer_email = $user->email;
        $comment->user_id = $user->id;
        $comment->created_at = now();
        $comment->post_id = $validated_request_items["post_id"];

        $comment->is_hidden = false; // or default to your model's value
        $comment->updated_at = null; // optional, usually auto-managed

        // Step 4: Try saving and log result
        try {
            if ($comment->save()) {
                Log::info("Comment.store - Comment successfully saved.");
                Log::info("Comment.store - Comment Store function END");
                return redirect()->back()->with('success', 'Comment posted!');
            } else {
                Log::warning("Comment.store - Comment.save() returned false.");
                Log::info("Comment.store - Comment Store function END");
                return redirect()->back()->withErrors('Failed to save comment.');
            }
        } catch (\Exception $e) {
            Log::error("Comment.store - Exception occurred: " . $e->getMessage());
            Log::info("Comment.store - Comment Store function END");
            return redirect()->back()->withErrors('An error occurred while saving your comment.');
        }
        Log::info("Comment.store - Comment Store function END");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Log::info("Comment.store - Comment Update function START");




        Log::info("Comment.store - Comment Update function END");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Log::info("Comment.destroy - Comment destroy function START");

        // Remember, users can still tamper the values from client side, so we don't want users to delete any comment without checking.

        // Step 1: Get the ID of the ;
        $comment_object = Comment::findOrFail($id);

        // Step 2: Check if the targeted comment belongs to the currently logged-in user
        $user_id_of_the_one_looking_at_this_page = Auth::id();
        $isOwner = $comment_object->user_id === $user_id_of_the_one_looking_at_this_page;

        // Step 3: Redirect user with error.
        if (!$isOwner) {
            abort(403, 'Unauthorized action.'); // Or redirect with an error
            return;
        }

        $comment_object->delete();
        Log::info("Comment.destroy - Comment $id was deleted. Function END");
        return redirect()->back()->with('success', 'Comment is GONE!');
    }
}
