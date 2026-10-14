<?php

namespace Tests\Feature;

use App\Models\Departure;
use App\Models\Inquiry;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InquiryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed();
    }

    private function payload(): array
    {
        return ['name' => 'Calon Jamaah', 'phone' => '081234567890', 'city' => 'Banjarmasin', 'travelers' => 2, 'message' => 'Rencana keluarga', 'consent' => '1'];
    }

    public function test_consultation_is_stored_and_whatsapp_retains_package_context(): void
    {
        $departure = Departure::publiclyVisible()->first();
        $response = $this->post('/konsultasi', $this->payload() + ['package_id' => $departure->package_id, 'departure_id' => $departure->id]);
        $response->assertRedirect(route('consultation.success'));
        $this->assertDatabaseCount('inquiries', 1);
        $inquiry = Inquiry::first();
        $this->assertSame('new', $inquiry->status);
        $this->assertNotNull($inquiry->consented_at);
        $this->get('/konsultasi/terkirim')->assertOk()->assertSee('https://wa.me/6289620339209')->assertSee(rawurlencode($departure->package->name));
    }

    public function test_consent_phone_and_honeypot_are_validated(): void
    {
        $this->post('/konsultasi', [...$this->payload(), 'phone' => '123', 'consent' => null, 'website' => 'spam'])->assertSessionHasErrors(['phone', 'consent', 'website']);
        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_schedule_must_be_active_and_belong_to_selected_package(): void
    {
        $departure = Departure::first();
        $other = Package::where('id', '!=', $departure->package_id)->first();
        $this->post('/konsultasi', $this->payload() + ['package_id' => $other->id, 'departure_id' => $departure->id])->assertSessionHasErrors('departure_id');
        $departure->update(['departure_date' => today()->subDay()]);
        $this->post('/konsultasi', $this->payload() + ['package_id' => $departure->package_id, 'departure_id' => $departure->id])->assertSessionHasErrors('departure_id');
        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_success_page_cannot_be_accessed_without_own_session(): void
    {
        $this->get('/konsultasi/terkirim')->assertNotFound();
    }
}
