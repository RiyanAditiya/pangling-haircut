<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the Booking "created" event.
     */
    public function created(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        // Pastikan model Booking Anda memiliki relasi 'services' dan field 'total_price'
        if ($booking->isDirty('status') && $booking->status === 'completed') {
            
            if (Transaction::where('booking_id', $booking->id)->exists()) {
                return; 
            }
            
            // Ambil nama layanan dari relasi many-to-many
            $serviceNames = $booking->services->pluck('name')->implode(', ');
            
            Transaction::create([
                'booking_id' => $booking->id,
                'customer_id' => $booking->customer_id,
                'barber_id' => $booking->barber_id, // barber_id adalah ID Barber
                'barbershop_id' => $booking->barbershop_id,
                'service_name' => $serviceNames,
                'type' => 'booking',
                'amount' => $booking->total_price, // Ambil dari field 'total_price' di tabel bookings
                'transaction_date' => now(),
            ]);
        }
    }

    /**
     * Handle the Booking "deleted" event.
     */
    public function deleted(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "restored" event.
     */
    public function restored(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "force deleted" event.
     */
    public function forceDeleted(Booking $booking): void
    {
        //
    }
}
