<?php

namespace Tests\Feature;

use App\Models\Departure;
use App\Models\FeatureBanner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomepageSectionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed();
    }

    public function test_homepage_contains_airline_carousel_social_and_schedule_sections(): void
    {
        $this->get('/')->assertOk()->assertSee('data-marquee', false)->assertSee('data-carousel', false)
            ->assertSee('social.webp')->assertSee('https://www.instagram.com/rofiqtour/')
            ->assertSee('data-schedule', false)->assertSee('data-schedule-month', false)
            ->assertSee('Silver')->assertSee('Platinum')->assertSee('Daftar tunggu');
    }

    public function test_schedule_markup_contains_every_active_departure_and_quad_tier_rates(): void
    {
        $departure = Departure::first();
        $departure->rates()->where('room_type', 'triple')->update(['price' => 10000]);
        $response = $this->get('/');
        $response->assertSee('Rp 30,9 jt');
        $this->assertSame(Departure::publiclyVisible()->count(), substr_count($response->getContent(), 'data-schedule-card'));
        $response->assertSee('data-month="'.$departure->departure_date->format('Y-m').'"', false);
    }

    public function test_unpublished_banner_and_cancelled_departure_are_not_displayed(): void
    {
        $draft = FeatureBanner::factory()->create(['title' => 'Private facility banner']);
        $departure = Departure::first();
        $departure->update(['status' => 'cancelled']);
        $response = $this->get('/')->assertOk()->assertDontSee($draft->title);
        $this->assertSame(Departure::publiclyVisible()->count(), substr_count($response->getContent(), 'data-schedule-card'));
    }

    public function test_banner_content_is_editable_by_admin_and_requires_an_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin)->post('/admin/banners', ['title' => 'Banner tanpa foto', 'description' => 'Deskripsi', 'sort_order' => 0])->assertSessionHasErrors('image');
        $this->post('/admin/banners', ['title' => 'Banner baru', 'description' => 'Deskripsi layanan', 'sort_order' => 1, 'is_published' => 1, 'image' => UploadedFile::fake()->image('banner.jpg')])->assertRedirect('/admin/banners');
        $banner = FeatureBanner::where('title', 'Banner baru')->first();
        Storage::disk('public')->assertExists($banner->image_path);
        $this->get('/')->assertSee('Banner baru');
    }
}
