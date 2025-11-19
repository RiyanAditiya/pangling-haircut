<?php

namespace App\Livewire\Customer;

use App\Models\User;
use Livewire\Component;
use App\Models\Schedule;
use App\Models\ServiceCategory;
use Livewire\Attributes\Layout;

class Home extends Component
{
    public $selectedCategory = null;
    public $showModal = false;

    public function showServices($categoryId)
    {
        $this->selectedCategory = ServiceCategory::with('services')->find($categoryId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }


    #[Layout('components.layouts.cust-app')]
    public function render()
    {
        $barbers = User::role('barber')->get();
        $categories = ServiceCategory::with('services')->get();
        $schedules = Schedule::with(['barber', 'barbershop'])
                        ->orderByRaw("FIELD(day_of_week, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
                        ->get()
                        ->groupBy('day_of_week');

        return view('livewire.customer.home', [
            'categories' => $categories,
            'schedules' => $schedules,
            'barbers' => $barbers,
        ]);
    }
}
