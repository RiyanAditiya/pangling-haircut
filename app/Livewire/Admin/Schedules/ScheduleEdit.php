<?php

namespace App\Livewire\Admin\Schedules;

use App\Models\Barbershop;
use App\Models\Schedule;
use App\Models\User;
use Livewire\Component;

class ScheduleEdit extends Component
{
    public $scheduleId;
    public $barber_id;
    public $barbershop_id;
    public $day_of_week;
    public $start_time;
    public $end_time;
    public $slot_duration = 60;
    public $is_day_off = false;

    public $barbers;
    public $barbershops;

    // pakai bahasa Inggris biar konsisten dengan enum di database
    public $days = [
        'monday','tuesday','wednesday','thursday','friday','saturday','sunday'
    ];

    public function mount($id)
    {
        $schedule = Schedule::findOrFail($id);

        $this->scheduleId     = $schedule->id;
        $this->barber_id        = $schedule->barber_id;
        $this->barbershop_id  = $schedule->barbershop_id;
        $this->day_of_week    = $schedule->day_of_week;
                
        // Format agar cocok dengan input type="time"
        $this->start_time     = $schedule->start_time ? date('H:i', strtotime($schedule->start_time)) : null;
        $this->end_time       = $schedule->end_time ? date('H:i', strtotime($schedule->end_time)) : null;

        $this->slot_duration  = $schedule->slot_duration;
        $this->is_day_off     = $schedule->is_day_off;

        $this->barbers        = User::role('barber')->get();
        $this->barbershops    = Barbershop::all();
    }

    protected function rules()
    {
        $rules = [
            'barber_id'      => 'required|exists:users,id',
            'day_of_week'  => 'required|in:' . implode(',', $this->days),
            'slot_duration'=> 'required|integer|min:15|max:480', // misal 15 menit sampai 8 jam
        ];

        if (!$this->is_day_off) {
            $rules['barbershop_id'] = 'required|exists:barbershops,id';
            $rules['start_time']    = 'required|date_format:H:i';
            $rules['end_time']      = 'required|date_format:H:i|after:start_time';
        }

        return $rules;
    }

    public function update()
    {
        $this->validate();

        $schedule = Schedule::findOrFail($this->scheduleId);

        $schedule->update([
            'barber_id'       => $this->barber_id,
            'barbershop_id' => $this->is_day_off ? null : $this->barbershop_id,
            'day_of_week'   => $this->day_of_week,
            'start_time'      => $this->is_day_off ? null : $this->start_time . ':00',
            'end_time'        => $this->is_day_off ? null : $this->end_time . ':00',
            'slot_duration' => $this->slot_duration,
            'is_day_off'    => $this->is_day_off,
        ]);

        session()->flash('success', 'Jadwal barber berhasil diperbarui!');
        return $this->redirectRoute('admin.schedule', navigate:true);
    }

    public function render()
    {
        return view('livewire.admin.schedules.schedule-edit', [
            'barbers'     => $this->barbers,
            'barbershops' => $this->barbershops,
        ]);
    }
}
