<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    protected $fillable = ['reference', 'package_id', 'departure_id', 'name', 'phone', 'city', 'travelers', 'message', 'status', 'admin_notes', 'consented_at'];

    protected function casts(): array
    {
        return ['consented_at' => 'datetime'];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class)->withTrashed();
    }

    public function departure(): BelongsTo
    {
        return $this->belongsTo(Departure::class)->withTrashed();
    }
}
