<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\PortalAuthenticator;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(private readonly PortalAuthenticator $portal)
    {
    }

    /**
     * Tampilkan formulir login portal kampus.
     */
    public function tampilkanForm(): View
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi via Portal Kampus.
     */
    public function proses(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'NIM atau NIP wajib diisi.',
            'password.required' => 'Password portal wajib diisi.',
        ]);

        // Autentikasi lewat driver portal kampus (Fake atau Bridge)
        $data = $this->portal->attempt($credentials['username'], $credentials['password']);

        if ($data === null) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors([
                    'username' => 'NIM/NIP atau password portal salah.',
                ]);
        }

        // Pastikan role yang diizinkan hanya mahasiswa dan dosen
        if (! in_array($data['role'], ['mahasiswa', 'dosen'], true)) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors([
                    'username' => 'Hanya mahasiswa dan dosen yang dapat mengakses sistem ini.',
                ]);
        }

        // firstOrNew user berdasarkan username
        $user = User::firstOrNew(['username' => $data['username']]);
        $user->name     = $data['nama'];
        $user->role     = $data['role'];
        $user->fakultas = $data['fakultas'] ?? null;
        $user->prodi    = $data['prodi'] ?? null;

        // Set status admin jika ditentukan dari payload autentikasi
        if (isset($data['is_admin'])) {
            $user->is_admin = (bool) $data['is_admin'];
        }

        // Isi no_wa dari no_hp HANYA jika no_wa masih kosong
        if (blank($user->no_wa) && filled($data['no_hp'] ?? null)) {
            $user->no_wa = $data['no_hp'];
        }

        $user->save();

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    /**
     * Keluar dari sesi aplikasi.
     */
    public function keluar(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
