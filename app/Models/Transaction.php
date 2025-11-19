<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'customer_name_manual', // opsional jika customer belum terdaftar
        'barber_id',
        'barbershop_id',
        'service_name',
        'type',               // walkin / booking
        'amount',             // total pembayaran
        'transaction_date',   // waktu transaksi
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /** RELASI */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function barber()
    {
        return $this->belongsTo(User::class, 'barber_id');
    }

    public function barbershop()
    {
        return $this->belongsTo(Barbershop::class);
    }
}
