<?php

namespace App\Livewire\Admin\Transactions;

use App\Models\Service;
use App\Models\Barbershop;
use App\Models\Transaction;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

class TransaksiCreate extends Component
{
    // 🧾 Input Data
    #[Validate('required|string|max:255')]
    public $customer_name_manual = ''; // Nama customer manual (bukan customer_id)

    #[Validate('required|array|min:1')]
    public $selected_service_ids = [];

    #[Validate('required|numeric|min:1000')]
    public $amount = 0;

    #[Validate('required|exists:barbershops,id')]
    public $barbershop_id;
    
    // Properti untuk menyimpan ID Barber yang sedang login (bukan input form)
    public $barber_id; 

    // 🧩 Data List
    public $allServices;
    public $barbershops;

    public function mount()
    {
        // Inisialisasi data yang dibutuhkan oleh form
        $this->allServices = Service::all();
        $this->barbershops = Barbershop::all();

        // Set ID Barber dari user yang sedang login saat komponen dimuat
        $this->barber_id = Auth::id(); 
    }

    // Hitung ulang total harga layanan setiap kali service dipilih/dihapus
    public function updatedSelectedServiceIds()
    {
        $this->amount = $this->allServices
            ->whereIn('id', $this->selected_service_ids)
            ->sum('price');
    }

    public function saveTransaction()
    {
        // 🔍 Validasi input
       $this->validate();

        // Pastikan barber_id terisi
        if (!$this->barber_id) {
            session()->flash('error', 'Kesalahan autentikasi. Barber ID tidak ditemukan.');
            return;
        }

        // 🪒 Gabungkan nama layanan
        $serviceNames = $this->allServices
            ->whereIn('id', $this->selected_service_ids)
            ->pluck('name')
            ->implode(', ');

        // 💾 Simpan transaksi
        Transaction::create([
            'booking_id' => null,
            'customer_id' => null, // Karena manual input (walk-in)
            'customer_name_manual' => $this->customer_name_manual,
            'barber_id' => $this->barber_id, // Menggunakan properti komponen
            'barbershop_id' => $this->barbershop_id,
            'service_name' => $serviceNames,
            'type' => 'walkin',
            'amount' => $this->amount,
            'transaction_date' => now(),
        ]);

        // ✅ Reset dan redirect. Menggunakan kunci 'success_message' agar notifikasi di Blade muncul.
        session()->flash('success_message', 'Transaksi Walk-in berhasil dicatat!'); 
        
        // Reset properti input
        $this->reset();

        // Arahkan ke halaman daftar transaksi staff
        return $this->redirectRoute('staff.transactions', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.transactions.transaksi-create');
    }
}
