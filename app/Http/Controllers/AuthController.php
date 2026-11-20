<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Halaman Login Admin
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Proses Login Admin dengan Rate Limiting & Audit Log
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // Cek jika akun sedang terkunci karena gagal login berulang (PRD AC-A02)
        if ($user && $user->locked_until && Carbon::now()->lessThan($user->locked_until)) {
            $sisaMenit = Carbon::now()->diffInMinutes($user->locked_until) + 1;
            AuditLog::log('login_blocked', 'auth', "Percobaan login pada akun terkunci: {$user->email}");

            return back()->withErrors([
                'email' => "Akun terkunci sementara karena terlalu banyak percobaan gagal. Silakan coba lagi dalam {$sisaMenit} menit.",
            ])->onlyInput('email');
        }

        if ($user && Hash::check($credentials['password'], $user->password)) {
            if ($user->status !== 'aktif') {
                return back()->withErrors([
                    'email' => 'Akun Anda dinonaktifkan oleh Administrator.',
                ]);
            }

            // Reset percobaan gagal
            $user->failed_login_attempts = 0;
            $user->locked_until = null;
            $user->last_login_at = now();
            $user->save();

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            AuditLog::log('login', 'auth', "Admin {$user->name} ({$user->email}) berhasil login");

            return redirect()->intended(route('admin.dashboard'));
        }

        // Catat kegagalan login
        if ($user) {
            $user->increment('failed_login_attempts');

            if ($user->failed_login_attempts >= 5) {
                $user->locked_until = Carbon::now()->addMinutes(15);
                $user->save();

                AuditLog::log('account_locked', 'auth', "Akun {$user->email} dikunci 15 menit setelah 5x gagal login");

                return back()->withErrors([
                    'email' => 'Terlalu banyak percobaan gagal. Akun dikunci sementara selama 15 menit untuk keamanan.',
                ])->onlyInput('email');
            }

            $user->save();
        }

        AuditLog::log('login_failed', 'auth', "Percobaan login gagal untuk email: {$credentials['email']}");

        // Pesan kesalahan generik (PRD AC-A01)
        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Logout Admin
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            AuditLog::log('logout', 'auth', "Admin {$user->name} telah keluar dari sistem");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil keluar.');
    }
}
