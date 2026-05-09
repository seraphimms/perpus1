<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        return view('profil.index', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama'    => 'required|string|max:100',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'alamat'  => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
        ], [
            'nama.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email sudah digunakan.',
        ]);

        $user->update($request->only('nama', 'email', 'alamat', 'telepon'));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama'         => 'required',
            'password'              => 'required|min:8|confirmed',
        ], [
            'password_lama.required'    => 'Password lama wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
        ]);

        if (!Hash::check($request->password_lama, auth()->user()->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.'])->with('tab', 'password');
        }

        auth()->user()->update(['password' => $request->password]);

        return back()->with('success', 'Password berhasil diperbarui.')->with('tab', 'password');
    }
}