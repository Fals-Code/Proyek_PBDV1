<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login user
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Coba login dengan username & password
        if (!Auth::attempt($request->only('username', 'password'), $request->boolean('remember'))) {
            return back()->withErrors([
                'username' => 'Username atau password salah.',
            ])->onlyInput('username');
        }

        // Regenerasi sesi untuk keamanan
        $request->session()->regenerate();

        // Arahkan ke dashboard setelah login berhasil
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Logout user
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
