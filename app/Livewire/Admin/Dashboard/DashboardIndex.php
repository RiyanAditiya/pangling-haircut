<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Booking;
use Livewire\Component;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

class DashboardIndex extends Component
{

    public int $totalBookingsToday = 0;
    public int $totalTransactionsToday = 0;
    public float $totalRevenueToday = 0.00;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $today = Carbon::today();

        $this->totalBookingsToday = Booking::whereDate('created_at', $today)->count();
        $this->totalTransactionsToday = Transaction::whereDate('transaction_date', $today)->count();
        $this->totalRevenueToday = Transaction::whereDate('transaction_date', $today)->sum('amount');
    }

    public function render()
    {
        return view('livewire.admin.dashboard.dashboard-index');
    }
}
