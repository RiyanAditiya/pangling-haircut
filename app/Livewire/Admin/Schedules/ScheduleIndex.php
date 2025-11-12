<?php

namespace App\Livewire\Admin\Schedules;

use App\Models\Schedule;
use Livewire\Component;
use Livewire\WithPagination;

class ScheduleIndex extends Component
{
    use WithPagination;

    public $deleteId = null;
    public $query;


    public function search()
    {
        $this->resetPage();
    }

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        if ($this->deleteId) {
            Schedule::findOrFail($this->deleteId)->delete();
            $this->deleteId = null;

            session()->flash('success', 'Jadwal barber berhasil dihapus!');
            return $this->redirectRoute('admin.schedule', navigate: true);
        }
    }

    public function render()
    {
        $schedules = Schedule::with(['barber', 'barbershop'])
            ->orderByDesc('created_at')
            ->when($this->query, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('barber', function ($sub) {
                        $sub->where('name', 'like', "%{$this->query}%");
                    })
                    ->orWhere('day_of_week', 'like', "%{$this->query}%");
                });
            })
            ->paginate(5);

        return view('livewire.admin.schedules.schedule-index', [
            'schedules' => $schedules,
        ]);
    }
}
