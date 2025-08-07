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
        $validated_requests = $request->validate([
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
        $comment->comment_context = $validated_requests["comment_context"];
        $comment->reviewer_name = $user->name;
        $comment->reviewer_email = $user->email;
        $comment->user_id = $user->id;
        $comment->created_at = now();
        $comment->post_id = $validated_requests["post_id"];

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
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Log::info("Comment.store - Comment Update function START");

        Log::info("What's the comment id?: $id");

        // Step 1: Validating Inputs
        $validated_requests = $request->validate([
            "updated_comment_context" => 'required|string|max:1000',
        ]);

        // Step 2: Query the comment
        $comment = Comment::where('id', $id)->first();

        // Step 3: Check if user is allowed to edit this comment.
        $user_id_of_the_one_looking_at_this_page = Auth::id();
        $user_owns_this_comment = $user_id_of_the_one_looking_at_this_page == $comment->user_id;
        Log::info("comment.update - Does user own this comment? $user_owns_this_comment");

        if ($user_owns_this_comment) {
            // Step 1: userverified = now update the comment and save it
            Log::info("comment.update - going to update the comment");

            $comment->comment_context = $validated_requests["updated_comment_context"];
            $comment->updated_at = now();
            Log::info("comment.update - updating comment context");
            $comment->save();
        } else {
            Log::info("ERROR: User doesn't own this comment");
            abort(403, 'Unauthorized action.'); // Or redirect with an error
            return;
        }

        Log::info("Comment.store - Comment Update function END");
        return redirect()->back()->with('success', 'Comment is updated!');
    }

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

    /////////// (Other Functions not part of CRUD)

    public function replyToComment(Request $request)
    {
        Log::info("replyToComment - replying to comment START");
        // debugging
        Log::info("What's in under_what_comment_id?", ['under_what_comment_id' => $request->input('under_what_comment_id')]);
        Log::info("What's in my comment_context?", ['comment_context' => $request->input('comment_context')]);

        // Step 1: validate the request
        $validated_requests = null;
        try {
            $validated_requests = $request->validate(rules: [
                "under_what_comment_id" => 'required|integer',
                "comment_context" => 'required|string|max:1000',
                "inside_what_post_id" => 'required|integer',
            ]);
        } catch (\Exception $e) {
            Log::info("CommentController@replyToComment - ERROR in validating request: $e");
            return back()->withErrors("CommentController@replyToComment - ERROR in validating request: $e");
        };

        // Step 2: Check if user is authenticated
        if (Auth::check() == false) {
            Log::warning("User not authenticated when trying to reply to comment.");
            return redirect()->back()->withErrors(['error' => 'User not authenticated.']);
        }

        // Step 3: Add the new subcomment
        $is_post_save_successful = false;
        if ($validated_requests != null) {
            // Step 1: Get current user name & email
            $user = Auth::user();
            $reviewer_name = $user->name;
            $reviewer_email = $user->email;


            // Step 2: Create the new comment
            $comment = new Comment();
            $comment->is_comment_a_subcomment = true;
            // name & email
            $comment->reviewer_name = $reviewer_name;
            $comment->reviewer_email = $reviewer_name;
            $comment->user_id = $user->id;
            // set the other necessary details
            $comment->created_at = now();
            $comment->comment_context = $validated_requests["comment_context"];
            $comment->under_what_comment_id_is_this_comment = $validated_requests["under_what_comment_id"];
            $comment->post_id = $validated_requests["inside_what_post_id"];

            try {
                $comment->save();
                $is_post_save_successful = true;

                Log::info("This current comment's id?", ['id' => $comment->id]);
            } catch (\Exception $e) {
                Log::info("CommentController@replyToComment - ERROR: post save wasn't successful: $e");
                return back()->withErrors("CommentController@replyToComment - ERROR: post save wasn't successful: $e");
            };
        }

        if ($is_post_save_successful) {
            return redirect()->back()->with('success', 'Reply posted!');
        } else {
            return back()->withErrors("CommentController@replyToComment - ERROR: post save wasn't successful: is_post_save wasn't set to TRUE");
        }
    }
}
