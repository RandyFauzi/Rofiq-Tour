<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Departure;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed();
    }

    public function test_public_pages_and_detail_templates_render(): void
    {
        foreach (['/', '/paket', '/artikel', '/faq', '/galeri', '/tentang', '/kontak', '/konsultasi', '/kebijakan-privasi', '/admin/login'] as $url) {
            $this->get($url)->assertOk();
        }
        $this->get(route('packages.show', Package::first()))->assertOk()->assertSee('Hotel Makkah');
        $this->get(route('articles.show', Article::first()))->assertOk();
        $this->get('/kontak')->assertSee('Banjarmasin')->assertSee('2QsarYz8mRQxtWzZ9');
    }

    public function test_unpublished_and_future_content_is_not_public(): void
    {
        $package = Package::first();
        $package->update(['is_published' => false]);
        $this->get(route('packages.show', $package))->assertNotFound();
        $this->get('/paket')->assertDontSee($package->name);
        $article = Article::first();
        $article->update(['published_at' => now()->addDay()]);
        $this->get(route('articles.show', $article))->assertNotFound();
    }

    public function test_catalog_filters_destination_month_and_empty_results(): void
    {
        $this->get('/paket?destination=turki')->assertOk()->assertSee('Umroh Plus Turki')->assertDontSee('Umroh Plus Dubai');
        $this->get('/paket?q=nonexistent-program')->assertOk()->assertSee('Belum ada program yang sesuai');
        $this->get('/paket?month=invalid')->assertSessionHasErrors('month');
        $this->get('/paket?month=1999-01')->assertOk()->assertSee('Belum ada program yang sesuai');
    }

    public function test_past_departures_do_not_influence_public_prices(): void
    {
        $package = Package::first();
        foreach ($package->departures as $index => $departure) {
            $departure->update(['departure_date' => today()->subYear()->addDays($index)]);
        }
        $this->get(route('packages.show', $package))->assertOk()->assertSee('Jadwal berikutnya sedang disiapkan')->assertDontSee('Rp 30.900.000');
        $this->assertSame(0, Departure::publiclyVisible()->where('package_id', $package->id)->count());
    }

    public function test_month_filter_uses_prices_from_the_selected_month(): void
    {
        $package = Package::first();
        $departure = $package->departures()->orderByDesc('departure_date')->first();
        $departure->rates()->update(['price' => 125000000]);
        $month = $departure->departure_date->format('Y-m');
        $this->get('/paket?destination=thaif&month='.$month)->assertOk()->assertSee('Rp 125.000.000')->assertDontSee('Rp 30.900.000');
    }

    public function test_faq_search_matches_answer_and_not_only_question(): void
    {
        $this->get('/faq?q=vaksin')->assertOk()->assertSee('Dokumen apa yang perlu saya siapkan?')->assertSee('1 jawaban ditemukan');
        $this->get('/faq?q=notfound')->assertSee('Belum menemukan jawaban?');
    }

    public function test_seeder_does_not_overwrite_edited_content_or_create_default_admin(): void
    {
        $package = Package::first();
        $package->update(['summary' => 'Ringkasan yang telah diedit']);
        $count = Package::count();
        $this->seed();
        $this->assertSame($count, Package::count());
        $this->assertSame('Ringkasan yang telah diedit', $package->fresh()->summary);
        $this->assertDatabaseCount('users', 0);
    }
}
