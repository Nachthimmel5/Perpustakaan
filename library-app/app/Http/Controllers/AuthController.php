<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Anggota;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        // Cek username & password
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            
            // Redirect sesuai role
            return $this->redirectByRole($user);
        }

        return back()->withErrors(['login' => 'Username atau password salah'])->withInput();
    }

    // Tampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses register anggota baru
    public function register(Request $request)
    {
        $request->validate([
            'nis' => 'required|integer|unique:anggota,nis',
            'nama' => 'required|string|max:50',
            'kelas' => 'required|string|max:50',
            'jurusan' => 'required|in:rpl,tkj,tr,tja',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Bikin data anggota
        $anggota = Anggota::create([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
        ]);

        // Bikin user
        User::create([
            'id_anggota' => $anggota->id_anggota,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'anggota',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Berhasil logout');
    }

    // Helper: Redirect berdasarkan role
    private function redirectByRole($user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('siswa.dashboard');
        }
    }
}