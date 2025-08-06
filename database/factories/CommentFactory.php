<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $user = User::all();
        $userID = $user->random()->id;

        $postID = Post::inRandomOrder()->value('id');

        return [
            'user_id' => $userID,
            'reviewer_name' => $user->find($userID)->name,
            'comment_context' => fake()->paragraph(),
            'reviewer_email' => $user->find($userID)->email,
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'is_comment_a_subcomment' => false,
            'under_what_comment_id_is_this_comment' => null,
        ];
    }
}
