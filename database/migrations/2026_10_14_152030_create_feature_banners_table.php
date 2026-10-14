<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feature_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('eyebrow', 100)->nullable();
            $table->string('description', 500);
            $table->string('image_path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->index(['is_published', 'sort_order']);
            $table->timestamps();
        });
        DB::table('site_settings')->where('id', 1)->whereNull('instagram_url')
            ->update(['instagram_url' => 'https://www.instagram.com/rofiqtour/']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_banners');
    }
};
