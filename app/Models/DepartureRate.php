<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartureRate extends Model
{
    protected $fillable = ['departure_id', 'makkah_hotel_id', 'madinah_hotel_id', 'tier', 'room_type', 'price'];

    protected function casts(): array
    {
        return ['price' => 'integer'];
    }

    public function departure(): BelongsTo
    {
        return $this->belongsTo(Departure::class);
    }

    public function makkahHotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'makkah_hotel_id');
    }

    public function madinahHotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'madinah_hotel_id');
    }
}
