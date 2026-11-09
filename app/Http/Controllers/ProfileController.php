<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormatter;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Halaman setting akun (menu avatar kanan-atas): ubah kata sandi dan
     * tanda tangan yang dicetak di report.
     */
    public function setting()
    {
        return view('profile.setting', [
            'signature' => auth()->user()->signature,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Kata sandi saat ini salah.'],
            ]);
        }

        auth()->user()->forceFill(['password' => Hash::make($request->password)])->save();

        return ResponseFormatter::success(null, 'Kata sandi berhasil diubah');
    }

    public function updateSignature(Request $request)
    {
        $request->validate([
            'signature' => 'required|string|max:300000',
        ]);

        // report menyematkan nilai ini langsung sebagai src gambar — pastikan
        // benar-benar PNG base64, bukan payload lain
        $prefix = 'data:image/png;base64,';
        $binary = str_starts_with($request->signature, $prefix)
            ? base64_decode(substr($request->signature, strlen($prefix)), true)
            : false;
        if ($binary === false || substr($binary, 0, 8) !== "\x89PNG\r\n\x1a\n") {
            throw ValidationException::withMessages([
                'signature' => ['Format tanda tangan tidak valid.'],
            ]);
        }

        auth()->user()->forceFill(['signature' => $request->signature])->save();

        return ResponseFormatter::success(null, 'Tanda tangan berhasil disimpan');
    }

    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        if (auth()->user()->id == 1) {
            return back()->withErrors(['not_allow_profile' => __('You are not allowed to change data for a default user.')]);
        }

        auth()->user()->update($request->all());

        return back()->withStatus(__('Profile successfully updated.'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        if (auth()->user()->id == 1) {
            return back()->withErrors(['not_allow_password' => __('You are not allowed to change the password for a default user.')]);
        }

        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withPasswordStatus(__('Password successfully updated.'));
    }
}
