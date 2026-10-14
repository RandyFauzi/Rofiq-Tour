<?php

namespace App\Http\Controllers;

use App\Http\Requests\InquiryRequest;
use App\Models\Inquiry;
use App\Services\WhatsApp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function store(InquiryRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['consent', 'website']);
        $inquiry = Inquiry::create([...$data, 'reference' => (string) Str::uuid(), 'consented_at' => now(), 'status' => 'new']);
        $request->session()->put('inquiry_reference', $inquiry->reference);

        return redirect()->route('consultation.success');
    }

    public function success(Request $request, WhatsApp $whatsApp): View
    {
        $inquiry = Inquiry::where('reference', $request->session()->get('inquiry_reference'))->firstOrFail();

        return view('pages.consultation-success', ['inquiry' => $inquiry, 'whatsappUrl' => $whatsApp->inquiryUrl($inquiry)]);
    }
}
