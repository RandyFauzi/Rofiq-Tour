<?php

namespace Tests\Feature;

use App\Admin\ContentRegistry;
use App\Models\Departure;
use App\Models\DepartureRate;
use App\Models\GalleryPhoto;
use App\Models\Hotel;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed();
    }

    private function admin(): User
    {
        $user = User::factory()->create();
        $user->forceFill(['is_admin' => true])->save();

        return $user;
    }

    public function test_guests_and_regular_users_cannot_access_admin(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
    }

    public function test_admin_can_login_and_logout(): void
    {
        $user = $this->admin();
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->post('/admin/logout')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_admin_can_change_password_with_current_password_verification(): void
    {
        $user = $this->admin();
        $this->actingAs($user)->get('/admin/akun')->assertOk();
        $this->put('/admin/akun', ['current_password' => 'wrong', 'password' => 'StrongPassword2026', 'password_confirmation' => 'StrongPassword2026'])->assertSessionHasErrors('current_password');
        $this->put('/admin/akun', ['current_password' => 'password', 'password' => 'StrongPassword2026', 'password_confirmation' => 'StrongPassword2026'])->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('StrongPassword2026', $user->fresh()->password));
    }

    public function test_all_admin_modules_render_and_unknown_resources_are_rejected(): void
    {
        $this->actingAs($this->admin())->get('/admin')->assertOk();
        foreach (ContentRegistry::all() as $key => $definition) {
            $this->get('/admin/'.$key)->assertOk();
            if ($definition['create'] ?? true) {
                $this->get('/admin/'.$key.'/create')->assertOk();
            }
            $record = $definition['model']::first();
            if ($record) {
                $this->get('/admin/'.$key.'/'.$record->id.'/edit')->assertOk();
            }
        }
        $this->get('/admin/users')->assertNotFound();
        $this->get('/admin/settings/create')->assertForbidden();
    }

    public function test_package_changes_and_related_facilities_are_saved_together(): void
    {
        $package = Package::first();
        $data = $package->only(['name', 'slug', 'destination_id', 'summary', 'description', 'sort_order']);
        $this->actingAs($this->admin())->put('/admin/packages/'.$package->id, [...$data, 'is_published' => '1', 'included' => "Tiket PP\nManasik", 'excluded' => 'Paspor', 'itinerary' => 'Hari pertama | Keberangkatan bersama'])->assertRedirect('/admin/packages');
        $this->assertSame(2, $package->features()->where('type', 'included')->count());
        $this->assertSame(1, $package->itineraryDays()->count());
        $this->assertDatabaseHas('itinerary_days', ['package_id' => $package->id, 'title' => 'Hari pertama']);
    }

    public function test_capacity_and_rate_uniqueness_are_validated(): void
    {
        $departure = Departure::first();
        $this->actingAs($this->admin())->put('/admin/departures/'.$departure->id, [...$departure->only(['package_id', 'airline_id', 'duration_days', 'departure_city', 'status', 'capacity', 'deposit_amount']), 'departure_date' => $departure->departure_date->format('Y-m-d'), 'available_seats' => 999])->assertSessionHasErrors('available_seats');
        $rate = DepartureRate::first();
        $this->post('/admin/rates', $rate->only(['departure_id', 'tier', 'room_type', 'price', 'makkah_hotel_id', 'madinah_hotel_id']))->assertSessionHasErrors('room_type');
    }

    public function test_rate_hotels_must_match_the_city(): void
    {
        $rate = DepartureRate::first();
        $payload = $rate->only(['departure_id', 'tier', 'room_type', 'price', 'makkah_hotel_id', 'madinah_hotel_id']);
        $payload['makkah_hotel_id'] = Hotel::where('city', 'Madinah')->first()->id;
        $this->actingAs($this->admin())->put('/admin/rates/'.$rate->id, $payload)->assertSessionHasErrors('makkah_hotel_id');
    }

    public function test_gallery_uploads_accept_images_and_reject_non_images(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin())->post('/admin/gallery', ['title' => 'Momen jamaah', 'sort_order' => 0, 'is_published' => 1, 'image' => UploadedFile::fake()->image('photo.jpg')])->assertRedirect('/admin/gallery');
        $photo = GalleryPhoto::where('title', 'Momen jamaah')->first();
        Storage::disk('public')->assertExists($photo->image_path);
        $this->post('/admin/gallery', ['title' => 'Berkas tidak valid', 'sort_order' => 0, 'image' => UploadedFile::fake()->create('payload.php', 1)])->assertSessionHasErrors('image');
    }

    public function test_referenced_master_data_cannot_be_deleted(): void
    {
        $hotel = Hotel::first();
        $this->actingAs($this->admin())->delete('/admin/hotels/'.$hotel->id)->assertSessionHasErrors('delete');
        $this->assertDatabaseHas('hotels',['id' => $hotel->id]);
    }
}
