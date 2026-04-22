<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            // Site identity
            $table->string('site_tagline')->nullable()->after('site_name');
            $table->string('site_favicon')->nullable()->after('site_tagline');
            $table->string('site_logo')->nullable()->after('site_favicon');

            // SEO — basic meta
            $table->string('meta_title')->nullable()->after('site_logo');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');

            // Open Graph / social sharing
            $table->string('meta_og_image')->nullable()->after('meta_keywords');
            $table->string('meta_og_type')->default('website')->after('meta_og_image');

            // Analytics
            $table->string('google_analytics_id')->nullable()->after('meta_og_type');
            $table->string('google_tag_manager_id')->nullable()->after('google_analytics_id');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->dropColumn([
                'site_tagline',
                'site_favicon',
                'site_logo',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'meta_og_image',
                'meta_og_type',
                'google_analytics_id',
                'google_tag_manager_id',
            ]);
        });
    }
};
