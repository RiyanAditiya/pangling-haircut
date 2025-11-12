<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'barber_id','barbershop_id','day_of_week',
        'start_time','end_time','slot_duration','is_day_off'
    ];

    public function barber()
    {
        return $this->belongsTo(User::class, 'barber_id');
    }

    public function barbershop()
    {
        return $this->belongsTo(Barbershop::class);
    }
}
