<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['brand_name', 'tagline', 'whatsapp_number', 'contact_email', 'instagram_url', 'license_number', 'about_text'];
}
