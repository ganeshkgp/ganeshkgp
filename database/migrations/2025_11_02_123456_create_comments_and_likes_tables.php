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
            $table->id();
            $table->foreignId('blog_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->text('content');
            $table->boolean('is_approved')->default(false);
            $table->integer('likes_count')->default(0);
            $table->timestamps();

            // Indexes for better performance
            $table->index(['blog_id', 'parent_id']);
            $table->index(['is_approved', 'created_at']);
        });

        // Create a separate table for comment likes to track which users liked which comments
        Schema::create('comment_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->timestamps();

            // Ensure a user can only like a comment once
            $table->unique(['comment_id', 'user_id']);
            $table->unique(['comment_id', 'ip_address']);
        });

        // Create a separate table for blog likes to track which users liked which blogs
        Schema::create('blog_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->timestamps();

            // Ensure a user can only like a blog once
            $table->unique(['blog_id', 'user_id']);
            $table->unique(['blog_id', 'ip_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_likes');
        Schema::dropIfExists('comment_likes');
        Schema::dropIfExists('comments');
    }
};