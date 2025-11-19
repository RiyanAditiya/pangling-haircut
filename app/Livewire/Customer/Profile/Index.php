<?php

namespace App\Livewire\Customer\Profile;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    // Properti publik untuk menampung data yang akan ditampilkan di view
    public ?User $user;
    public $name;
    public $email;
    public $oldImage; // Properti untuk menampung path foto profil saat ini

    /**
     * Metode ini dijalankan saat komponen diinisialisasi.
     * Digunakan untuk memuat data pengguna yang sedang login.
     */
    public function mount()
    {
        // Pastikan pengguna sudah login
        if (Auth::check()) {
            // Ambil objek user dan simpan ke properti
            $this->user = Auth::user();
            
            // Isi properti dengan data pengguna
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->oldImage = $this->user->profile_photo_path; // Ganti dengan nama kolom foto profil Anda
        } else {
            // Arahkan ke halaman login jika belum login
            return redirect()->route('login');
        }
    }

    /**
     * Metode render untuk menampilkan view.
     */
    #[Layout('components.layouts.cust-app')]
    public function render()
    {
        return view('livewire.customer.profile.index');
    }
}
