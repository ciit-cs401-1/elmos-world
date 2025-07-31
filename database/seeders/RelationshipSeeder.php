<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Tag;

class RelationshipSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $roles = Role::pluck('id')->toArray();

        foreach ($users as $user) {

            $roleID = null;

            $roleID = fake()->randomElement($roles);

            DB::table('user_role')->insert([
                'user_id' => $user->id,
                'role_id' => $roleID
            ]);

            if ($user->email === 'admin@test.com') {
                DB::table('user_role')->where('user_id', 1)->update(['role_id' => 1]);
            }

            if ($user->email = "contributor@test.com") {
                DB::table('user_role')->where('user_id', 2)->update(['role_id' => 2]);
            }

            if ($user->email = "subscriber@test.com") {
                DB::table('user_role')->where('user_id', 3)->update(['role_id' => 3]);
            }
        }

        $posts = Post::all();
        $tags = Tag::pluck('id')->toArray();
        $categories = Category::pluck('id')->toArray();

        foreach ($posts as $post) {
            DB::table('post_tag')->insert([
                'post_id' => $post->id,
                'tag_id' => fake()->randomElement($tags),
            ]);

            DB::table('post_category')->insert([
                'post_id' => $post->id,
                'category_id' => fake()->randomElement($categories),
            ]);
        }
    }
}
