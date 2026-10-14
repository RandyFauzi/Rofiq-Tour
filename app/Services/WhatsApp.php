<?php

namespace App\Services;

use App\Models\Inquiry;
use App\Models\SiteSetting;

class WhatsApp
{
    public function inquiryUrl(Inquiry $inquiry): ?string
    {
        $number = SiteSetting::first()?->whatsapp_number;
        if (! $number) {
            return null;
        }
        $inquiry->loadMissing(['package', 'departure']);
        $message = "Assalamualaikum Rofiq Tour, saya {$inquiry->name}.\nReferensi: {$inquiry->reference}\n";
        $message .= 'Program: '.($inquiry->package?->name ?? 'Minta rekomendasi')."\n";
        if ($inquiry->departure) {
            $message .= 'Keberangkatan: '.$inquiry->departure->departure_date->format('d-m-Y')."\n";
        }
        $message .= "Jumlah jamaah: {$inquiry->travelers}\nDomisili: {$inquiry->city}\n{$inquiry->message}";

        return 'https://wa.me/'.$number.'?text='.rawurlencode($message);
    }
}
