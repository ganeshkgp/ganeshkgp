<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table): void {
            $table->id();

            // Hero section
            $table->string('hero_greeting')->default('Namaste, I\'m');
            $table->string('hero_name')->default('Arjun Sharma');
            $table->string('hero_tagline')->default('Freelance UX/UI Designer & Full-Stack Developer');
            $table->text('hero_bio')->nullable();
            $table->string('hero_image')->nullable();

            // About section
            $table->string('about_title')->default('Turning Bold Ideas Into Impactful Digital Products');
            $table->text('about_bio')->nullable();
            $table->string('about_cv_url')->nullable();
            $table->string('about_photo')->nullable();
            $table->json('skills')->nullable();

            // Contact section
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_address')->nullable();

            // Social links
            $table->string('social_facebook')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->string('social_github')->nullable();

            // Footer / misc
            $table->string('site_name')->default('Portfo.');
            $table->text('footer_description')->nullable();
            $table->json('brands')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
