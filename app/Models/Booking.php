<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'barber_id',
        'barbershop_id',
        'booking_date',
        'booking_time',
        'status',
        'proof_of_payment',
        'total_price'
    ];


    protected $casts = [
        'booking_date' => 'date', // 👈 WAJIB: Ubah string tanggal menjadi objek Carbon
        'booking_time' => 'datetime', // Opsional, tetapi disarankan untuk jam
        // ... casting lainnya
    ];

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

    // Services yang dipilih (many-to-many)
    public function services()
    {
        return $this->belongsToMany(Service::class, 'booking_service')->withTimestamps();
    }

    // Cek apakah booking masih aktif
    public function isActive()
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

}
