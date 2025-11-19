<?php

namespace App\Livewire\Customer;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class CustBookingIndex extends Component
{
    use WithPagination;

    public $query = '';
    public $cancelledId = null;

    protected $queryString = [
        'query' => ['except' => ''],
    ];

    public function updatedQuery()
    {
        // Mereset halaman pagination saat query pencarian berubah
        $this->resetPage();
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
            // Batasi pembatalan hanya jika status sudah final (cancelled, completed, rejected)
            session()->flash('error', 'Booking ini tidak dapat dibatalkan karena statusnya sudah final.');
        } else {
            // PERBAIKAN: Status yang diizinkan untuk dibatalkan adalah pending, pending_verification, atau confirmed.
            // 3. Proses pembatalan
            $booking->status = 'cancelled';
            $booking->save();

            session()->flash('success', 'Booking berhasil dibatalkan!');
        }
        
        // Reset ID dan refresh tampilan
        $this->cancelledId = null;
        $this->resetPage();
    }

    #[Layout('components.layouts.cust-app')]
    public function render()
    {
        $bookings = Booking::with(['customer', 'barber', 'barbershop', 'services']) 
            ->where('customer_id', Auth::user()->id) // hanya booking milik customer login
            ->when($this->query, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $search = '%' . $this->query . '%';
                    
                    // Pencarian di relasi Barber
                    $q->orWhereHas('barber', fn(Builder $sub) => $sub->where('name', 'like', $search));
                    
                    // Pencarian di relasi Barbershop
                    $q->orWhereHas('barbershop', fn(Builder $sub) => $sub->where('name', 'like', $search));
                    
                    // Pencarian di relasi Many-to-Many 'services'
                    $q->orWhereHas('services', fn(Builder $sub) => $sub->where('name', 'like', $search));
                    
                    // Tambahan: Pencarian berdasarkan ID booking
                    if (is_numeric($this->query)) {
                        $q->orWhere('id', $this->query);
                    }
                });
            })
            // Mengurutkan berdasarkan tanggal dan waktu booking terbaru
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10); 

        return view('livewire.customer.cust-booking-index', [
            'bookings' => $bookings,
        ]);
    }
}
