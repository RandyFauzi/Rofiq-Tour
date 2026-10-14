<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;

    protected $fillable = ['destination_id', 'name', 'slug', 'summary', 'description', 'image_path', 'is_published', 'is_featured', 'sort_order'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'is_featured' => 'boolean'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function departures(): HasMany
    {
        return $this->hasMany(Departure::class);
    }

    public function features(): HasMany
    {
        return $this->hasMany(PackageFeature::class)->orderBy('sort_order');
    }

    public function itineraryDays(): HasMany
    {
        return $this->hasMany(ItineraryDay::class)->orderBy('day_number');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeCatalog(Builder $query): Builder
    {
        return $query->published()->with(['destination', 'departures' => fn ($q) => $q->upcoming()->with(['airline', 'rates'])->orderBy('departure_date')]);
    }

    public function startingPrice(): ?int
    {
        $price = $this->departures->flatMap->rates->min('price');

        return $price === null ? null : (int) $price;
    }
}
