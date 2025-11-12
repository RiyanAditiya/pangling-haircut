<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingIndex extends Component
{
    use WithPagination;

    public $proofImage = null;
    public $statusModalId = null;
    public $newStatus = null;
    public $searchDate= null;

    public function search()
    {
        $this->resetPage();
    }

    public function updatedSearchDate()
    {
        $this->resetPage();
    }

    public function resetSearch()
    {
        $this->reset('searchDate');
        $this->resetPage();
    }

    public function showProof($path)
    {
        $this->proofImage = $path;
    }

    public function openStatusModal($id)
    {
        $this->statusModalId = $id;
        $this->newStatus = null;
    }

    public function updateStatus()
    {
        if (!$this->statusModalId || !$this->newStatus) return;

        $booking = \App\Models\Booking::find($this->statusModalId);
        if ($booking) {
            $booking->status = $this->newStatus;
            $booking->save();
            session()->flash('success', 'Status booking berhasil diperbarui.');
        }

        $this->statusModalId = null;
        $this->newStatus = null;
    }


    public function render()
    {
        $bookingQuery = Booking::with(['barber', 'barbershop', 'services', 'customer'])
            ->where('barber_id', Auth::user()->id);

        // Jika ada tanggal pencarian, filter berdasarkan tanggal created_at
        if ($this->searchDate) {
            try {
                $date = Carbon::parse($this->searchDate)->startOfDay();
                $bookingQuery->whereDate('booking_date', $date);
            } catch (\Exception $e) {
                // Jika format tanggal salah, abaikan filter
            }
        }

        $bookings = $bookingQuery
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.admin.bookings.booking-index', [
            'bookings' => $bookings
        ]);
    }
}
