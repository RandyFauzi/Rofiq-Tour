<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->boolean('is_admin')->default(false));
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->string('country', 100);
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
        Schema::create('airlines', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 10)->unique();
            $table->timestamps();
        });
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('city', 100);
            $table->unsignedTinyInteger('stars')->default(4);
            $table->unsignedInteger('distance_meters')->nullable();
            $table->timestamps();
            $table->unique(['name', 'city']);
        });
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->restrictOnDelete();
            $table->string('name', 150);
            $table->string('slug', 180)->unique();
            $table->string('summary', 500);
            $table->text('description');
            $table->string('image_path')->nullable();
            $table->boolean('is_published')->default(false)->index();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('package_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20);
            $table->string('description', 255);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('itinerary_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('day_number');
            $table->string('title', 150);
            $table->text('description');
            $table->timestamps();
            $table->unique(['package_id', 'day_number']);
        });
        Schema::create('departures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->restrictOnDelete();
            $table->foreignId('airline_id')->constrained()->restrictOnDelete();
            $table->date('departure_date');
            $table->unsignedSmallInteger('duration_days');
            $table->string('departure_city', 100)->default('Jakarta');
            $table->string('status', 20)->default('tentative');
            $table->unsignedSmallInteger('capacity')->default(40);
            $table->unsignedSmallInteger('available_seats')->default(40);
            $table->unsignedBigInteger('deposit_amount')->default(10000000);
            $table->softDeletes();
            $table->timestamps();
            $table->index(['departure_date', 'status']);
            $table->unique(['package_id', 'airline_id', 'departure_date', 'duration_days'], 'departure_schedule_unique');
        });
        Schema::create('departure_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departure_id')->constrained()->cascadeOnDelete();
            $table->foreignId('makkah_hotel_id')->constrained('hotels')->restrictOnDelete();
            $table->foreignId('madinah_hotel_id')->constrained('hotels')->restrictOnDelete();
            $table->string('tier', 20);
            $table->string('room_type', 20);
            $table->unsignedBigInteger('price');
            $table->timestamps();
            $table->unique(['departure_id', 'tier', 'room_type']);
        });
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->string('category', 80);
            $table->string('excerpt', 500);
            $table->text('body');
            $table->string('image_path')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('category', 80);
            $table->string('question', 255);
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('city', 100);
            $table->text('address');
            $table->string('phone', 25)->nullable();
            $table->string('map_url', 500)->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 150);
            $table->string('image_path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('city', 100);
            $table->text('quote');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name', 100)->default('Rofiq Tour');
            $table->string('tagline', 200)->default('Menemani setiap langkah menuju Baitullah.');
            $table->string('whatsapp_number', 20)->nullable();
            $table->string('contact_email', 150)->nullable();
            $table->string('instagram_url', 500)->nullable();
            $table->string('license_number', 100)->nullable();
            $table->text('about_text')->nullable();
            $table->timestamps();
        });
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference')->unique();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('departure_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 150);
            $table->string('phone', 25);
            $table->string('city', 100)->nullable();
            $table->unsignedSmallInteger('travelers')->default(1);
            $table->text('message')->nullable();
            $table->string('status', 20)->default('new')->index();
            $table->text('admin_notes')->nullable();
            $table->timestamp('consented_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['inquiries', 'site_settings', 'testimonials', 'gallery_photos', 'offices', 'faqs', 'articles', 'departure_rates', 'departures', 'itinerary_days', 'package_features', 'packages', 'hotels', 'airlines', 'destinations'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('is_admin'));
    }
};
