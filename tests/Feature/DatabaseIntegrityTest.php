<?php

namespace Tests\Feature;

use App\Models\Departure;
use App\Models\DepartureRate;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseIntegrityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_database_rejects_seats_above_capacity_even_without_a_form(): void
    {
        $this->expectException(QueryException::class);
        DB::table('departures')->where('id', Departure::first()->id)->update(['available_seats' => 999]);
    }

    public function test_database_rejects_non_positive_prices(): void
    {
        $this->expectException(QueryException::class);
        DB::table('departure_rates')->where('id', DepartureRate::first()->id)->update(['price' => 0]);
    }

    public function test_database_rejects_duplicate_room_prices(): void
    {
        $this->expectException(QueryException::class);
        $rate = DepartureRate::first();
        DB::table('departure_rates')->insert($rate->only(['departure_id', 'tier', 'room_type', 'price', 'makkah_hotel_id', 'madinah_hotel_id']));
    }

    public function test_departure_dates_are_stored_as_dates_and_can_be_seeded_again(): void
    {
        $departure = Departure::first();
        $this->assertSame($departure->departure_date->format('Y-m-d'), DB::table('departures')->where('id', $departure->id)->value('departure_date'));
        $count = Departure::count();
        $this->seed();
        $this->assertSame($count, Departure::count());
    }

    public function test_constraints_migration_can_resume_without_duplicate_constraints(): void
    {
        $migration = require database_path('migrations/2026_10_14_141155_enforce_travel_constraints.php');
        $migration->up();
        $migration->up();

        $this->assertSame(1, DB::table('site_settings')->count());
        $this->expectException(QueryException::class);
        DB::table('departures')->where('id', Departure::first()->id)->update(['available_seats' => 999]);
    }

    public function test_database_rejects_a_second_settings_identity(): void
    {
        $this->expectException(QueryException::class);
        DB::table('site_settings')->insert(['id' => 2, 'brand_name' => 'Invalid second settings']);
    }
}
