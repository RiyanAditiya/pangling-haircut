<?php

namespace App\Livewire\Customer;

use App\Models\Booking;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class CustBookingDetail extends Component
{
    public Booking $booking;
    public $cancelledId = null;


    public function mount($id)
    {
        $this->booking = Booking::with(['customer', 'barber', 'barbershop', 'services'])
                                ->where('customer_id', Auth::id())
                                ->findOrFail($id);
    }

    public function confirmCancel($id)
    {
        // Memastikan ID adalah numerik sebelum menyimpannya ke state
        if (is_numeric($id)) {
            $this->cancelledId = $id;
        }
    }

    public function cancelBooking()
    {
        // Memastikan ID pembatalan tersedia
        if (!$this->cancelledId) {
            session()->flash('error', 'ID Booking tidak valid.');
            return;
        }

        // 1. Ambil booking dan pastikan itu milik pengguna yang login
        $booking = Booking::where('id', $this->cancelledId)
                          ->where('customer_id', Auth::id())
                          ->first();

        // 2. Lakukan validasi keamanan dan status
        if (!$booking) {
            session()->flash('error', 'Booking tidak ditemukan atau Anda tidak memiliki izin untuk membatalkannya.');
        } elseif (in_array($booking->status, ['cancelled', 'completed'])) {
            // Batasi pembatalan hanya jika status sudah final (cancelled, completed)
            session()->flash('error', 'Booking ini tidak dapat dibatalkan karena statusnya sudah final.');
        } else {
            // PERBAIKAN: Status yang diizinkan untuk dibatalkan adalah pending, pending_verification, atau confirmed.
            // 3. Proses pembatalan
            $booking->status = 'cancelled';
            $booking->save();

            session()->flash('success', 'Booking berhasil dibatalkan!');

            return $this->redirectRoute('customer.booking', navigate:true);
        }
        
        // Reset ID dan refresh tampilan
        $this->cancelledId = null;
    }

    #[Layout('components.layouts.cust-app')]
    public function render()
    {
        // Livewire secara otomatis meneruskan properti publik ke view
        return view('livewire.customer.cust-booking-detail');
    }
}
