<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = Post::whereNotNull('publication_date')->get();

        foreach ($posts as $post) {
            // First create top-level comments
            $topLevelComments = Comment::factory(rand(2, 5))->create([
                'post_id' => $post->id,
                'is_comment_a_subcomment' => false,
                'under_what_comment_id_is_this_comment' => null,
            ]);

            // Then, randomly create subcomments for some of those top-level comments
            foreach ($topLevelComments as $topComment) {
                if (rand(0, 1)) {
                    Comment::factory(rand(1, 3))->create([
                        'post_id' => $post->id,
                        'is_comment_a_subcomment' => true,
                        'under_what_comment_id_is_this_comment' => $topComment->id,
                    ]);
                }
            }
        }
    }
}
