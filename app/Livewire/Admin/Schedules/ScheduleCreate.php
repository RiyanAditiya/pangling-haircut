<?php

namespace App\Livewire\Admin\Schedules;

use App\Models\Barbershop;
use App\Models\Schedule;
use App\Models\User;
use Livewire\Component;

class ScheduleCreate extends Component
{
    public $barber_id;
    public $barbershop_id;
    public $day_of_week;
    public $start_time;
    public $end_time;
    public $slot_duration = 60;
    public $is_day_off = false;

    // Sesuai enum di migration
    public $days = [
        'monday'    => 'Monday',
        'tuesday'   => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday'  => 'Thursday',
        'friday'    => 'Friday',
        'saturday'  => 'Saturday',
        'sunday'    => 'Sunday',
    ];

    protected function rules()
    {
        $rules = [
            'barber_id'     => 'required|exists:users,id',
            'day_of_week' => 'required|in:' . implode(',', array_keys($this->days)),
        ];

        if (!$this->is_day_off) {
            $rules['barbershop_id'] = 'required|exists:barbershops,id';
            $rules['start_time']    = 'required|date_format:H:i';
            $rules['end_time']      = 'required|date_format:H:i|after:start_time';
            $rules['slot_duration'] = 'required|integer|min:15|max:180';
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        Schedule::create([
            'barber_id'       => $this->barber_id,
            'barbershop_id' => $this->is_day_off ? null : $this->barbershop_id,
            'day_of_week'   => $this->day_of_week,
            'start_time'    => $this->is_day_off ? null : $this->start_time,
            'end_time'      => $this->is_day_off ? null : $this->end_time,
            'slot_duration' => $this->is_day_off ? 0 : $this->slot_duration,
            'is_day_off'    => $this->is_day_off,
        ]);

        $this->reset([
            'barber_id','barbershop_id','day_of_week','start_time','end_time','slot_duration','is_day_off'
        ]);

        session()->flash('success', 'Jadwal barber berhasil ditambahkan!');
        return $this->redirectRoute('admin.schedule', navigate:true);
    }

    public function render()
    {
        return view('livewire.admin.schedules.schedule-create', [
            'barbers'     => User::role('barber')->get(),
            'barbershops' => Barbershop::all(),
            'days'        => $this->days,
        ]);
    }
}
