<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departure;
use App\Models\Inquiry;
use App\Models\Package;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => ['Paket aktif' => Package::published()->count(), 'Jadwal mendatang' => Departure::publiclyVisible()->count(), 'Konsultasi baru' => Inquiry::where('status', 'new')->count()],
            'inquiries' => Inquiry::with('package')->latest()->limit(5)->get(),
        ]);
    }
}
