<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Wadir;
use App\Models\Kaprodi;
use App\Models\Direktur;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view("pages.auth.login");
    }


    public function processLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'role' => 'required'
        ]);
        $role = $request->role;
        $user = null;

        if ($role === 'admin') {
            $user = Admin::where('email', $request->username)->first();
            $guard = 'admin';
        } elseif ($role === 'direktur') {
            $user = Direktur::where('email', $request->username)->first();
            $guard = 'direktur';
        } elseif ($role === 'wakil_direktur') {
            $user = Wadir::where('email', $request->username)->first();
            $guard = 'wakil_direktur';
        } elseif ($role === 'kaprodi') {
            $user = Kaprodi::where('email', $request->username)->first();
            $guard = 'kaprodi';
        } elseif ($role === 'mahasiswa') {
            $user = Mahasiswa::where('nim', $request->username)->first();
            $guard = 'mahasiswa';
        } elseif ($role === 'dosen') {
            $user = Dosen::where('email', $request->username)->first();
            $guard = 'dosen';
        }
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::guard($guard)->login($user);
            session(['user' => [
                'id' => $user->id,
                'kelasId' => $user->kelas_id,
                'nama' => $user->nama ?? $user->nama_lengkap,
                'role' => $role,
                'wadir' => $user->no,
                'prodiId' => $user->prodis_id,
                'email' => $user->email,
                'status_pa' => $user->pembimbing_akademik,
            ]]);
            return redirect()->route('dashboard');
        } else
            return back()->withErrors(['error' => 'Username atau Password salah']);
    }

    public function logout(Request $request)
    {
        $role = session('user.role');

        switch ($role) {
            case 'admin':
                Auth::guard('admin')->logout();
                break;
            case 'direktur':
                Auth::guard('direktur')->logout();
                break;
            case 'wakil_direktur':
                Auth::guard('wakil_direktur')->logout();
                break;
            case 'kaprodi':
                Auth::guard('kaprodi')->logout();
                break;
            case 'mahasiswa':
                Auth::guard('mahasiswa')->logout();
                break;
            case 'dosen':
                Auth::guard('dosen')->logout();
                break;
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
