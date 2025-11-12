<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Login extends Component
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Email atau password yang Anda masukkan salah!',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        // Dapatkan objek user yang baru login
        $user = Auth::user();

        // 1. Cek Role Admin atau Barber (Role yang masuk ke Dashboard Backend)
        // Menggunakan hasAnyRole untuk mengecek dua role sekaligus
        if ($user->hasAnyRole(['admin', 'barber'])) {
            // Redirect Admin atau Barber ke /admin/dashboard
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
            
        // 2. Cek Role Customer
        } elseif ($user->hasRole('customer')) {
            // Redirect Customer ke halaman utama (/)
            $this->redirectIntended(default: route('home', absolute: false), navigate: true);
            
        } else {
            // Fallback: Jika role tidak terdefinisi (seharusnya tidak terjadi jika seeder benar)
            $this->redirectIntended(default: route('home', absolute: false), navigate: true);
        }
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
