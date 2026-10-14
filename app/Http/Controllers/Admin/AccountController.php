<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function edit(): View
    {
        return view('admin.account');
    }

    public function update(ChangePasswordRequest $request): RedirectResponse
    {
        $request->user()->update(['password' => $request->validated('password')]);
        Auth::logoutOtherDevices($request->validated('password'));
        $request->session()->regenerate();

        return back()->with('success', 'Kata sandi berhasil diubah.');
    }
}
