<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItineraryDay extends Model
{
    protected $fillable = ['package_id', 'day_number', 'title', 'description'];
}
