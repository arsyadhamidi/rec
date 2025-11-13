<?php

namespace App\Http\Controllers\Autentikasi;

use App\Http\Controllers\Controller;
use App\Models\LogUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('autentikasi.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        if (Auth::attempt($credentials)) {
            $users = Auth::user();
            $carbons = Carbon::now();
            if ($users->is_deleted == 1) {
                $request->session()->regenerate();

                // ✅ Ambil dan simpan token SatuSehat
                LogUser::create([
                    'user_id' => $users->id,
                    'aktivitas' => 'Log-In Pengguna',
                    'user' => $users->name,
                    'tanggal' => Carbon::now()->toDateString(),
                    'waktu_dibuat' => Carbon::now(),
                ]);

                return redirect()->intended('/dashboard');
            } else {
                Auth::logout();
                $request->session()->invalidate();
                return redirect('/')->with('error', 'Maaf ! Akun anda belum aktif silahkan hubungi teknisi!');
            }
        } else {
            return back()->with('error', 'Maaf | Username atau password anda salah !');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda berhasil logout!');
    }
}
