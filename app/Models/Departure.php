<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departure extends Model
{
    use SoftDeletes;

    protected $fillable = ['package_id', 'airline_id', 'departure_date', 'duration_days', 'departure_city', 'status', 'capacity', 'available_seats', 'deposit_amount'];

    protected function casts(): array
    {
        return ['departure_date' => 'date', 'deposit_amount' => 'integer'];
    }

    public function setDepartureDateAttribute(mixed $value): void
    {
        $this->attributes['departure_date'] = Carbon::parse($value)->toDateString();
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function airline(): BelongsTo
    {
        return $this->belongsTo(Airline::class);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(DepartureRate::class)->orderBy('price');
    }

    public function scopeUpcoming(Builder $q): Builder
    {
        return $q->whereDate('departure_date', '>=', today())->whereIn('status', ['confirmed', 'tentative'])->whereHas('rates');
    }

    public function scopePubliclyVisible(Builder $q): Builder
    {
        return $q->upcoming()->whereHas('package', fn ($p) => $p->published());
    }
}
