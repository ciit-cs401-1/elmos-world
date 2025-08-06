<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id('id');
            $table->text('comment_context');
            $table->string('reviewer_name')->nullable();
            $table->string('reviewer_email')->nullable();
            $table->boolean('is_hidden')->default(false);
            $table->timestamps();

            // This one is for the subcommenting
            $table->boolean("is_comment_a_subcomment")->default(false);
            $table->integer("under_what_comment_id_is_this_comment")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
