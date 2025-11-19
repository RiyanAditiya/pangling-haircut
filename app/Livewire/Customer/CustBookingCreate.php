<?php

namespace App\Livewire\Customer;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Booking;
use App\Models\Service;
use Livewire\Component;
use App\Models\Schedule;
use Carbon\CarbonPeriod;
use App\Models\Barbershop;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class CustBookingCreate extends Component
{
    use WithFileUploads;

    // Properti utama
    public $barbershops, $services, $selectedServices = [], $totalPrice = 0;
    public $barbershop_id, $booking_date, $barber_id, $booking_time, $proof_of_payment;
    public $barbers = [], $availableTimes = [], $slotDuration = 60;

    protected $rules = [
        'barbershop_id' => 'required|exists:barbershops,id',
        'booking_date' => 'required|date|after_or_equal:today',
        'barber_id' => 'required|exists:users,id',
        'booking_time' => 'required|date_format:H:i',
        'selectedServices' => 'required|array|min:1',
        'selectedServices.*' => 'exists:services,id',
        'proof_of_payment' => 'required|image|max:2048',
    ];

    public function mount()
    {
        $this->barbershops = Barbershop::all();
        $this->services = Service::select('id', 'name', 'price')->get();
        $this->booking_date = Carbon::today()->toDateString();
    }

    // Hooks pembaruan
    public function updatedBarbershopId()
    {
        $this->resetSelection();
        $this->loadBarbers();
    }

    public function updatedBookingDate()
    {
        $this->resetSelection();
        $this->loadBarbers();
    }

    public function updatedBarberId()
    {
        $this->booking_time = null;
        $this->loadAvailableTimes();
    }

    public function updatedSelectedServices()
    {
        $this->totalPrice = 0;
        $allServices = $this->services->keyBy('id');
        foreach ($this->selectedServices as $serviceId) {
            $service = $allServices->get((int)$serviceId);
            if ($service) {
                $this->totalPrice += $service->price ?? 0;
            }
        }
    }

    // Helper methods
    protected function resetSelection()
    {
        $this->barber_id = null;
        $this->booking_time = null;
        $this->availableTimes = [];
    }

    public function loadBarbers()
    {
        $this->barbers = [];
        if (!$this->barbershop_id || !$this->booking_date) return;

        $dayOfWeek = strtolower(Carbon::parse($this->booking_date)->format('l'));
        $barbers = User::role('barber')
            ->whereHas('schedules', function ($q) use ($dayOfWeek) {
                $q->where('day_of_week', $dayOfWeek)
                    ->where('is_day_off', false)
                    ->where('barbershop_id', $this->barbershop_id);
            })
            ->get();

        $this->barbers = $barbers->isEmpty() ? [] : $barbers;

        if ($barbers->isEmpty()) {
            $barbershopName = Barbershop::find($this->barbershop_id)->name ?? 'Barbershop ini';
            session()->flash('warning', "Maaf, $barbershopName tidak memiliki Barber yang bekerja pada tanggal tersebut.");
        }
    }

    public function loadAvailableTimes()
    {
        $this->availableTimes = [];
        if (!$this->barber_id || !$this->barbershop_id || !$this->booking_date) return;

        $dayOfWeek = strtolower(Carbon::parse($this->booking_date)->format('l'));
        $schedule = Schedule::where('barber_id', $this->barber_id)
            ->where('barbershop_id', $this->barbershop_id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_day_off', false)
            ->first();

        if (!$schedule) {
            session()->flash('warning', "Barber yang Anda pilih tidak memiliki jadwal kerja pada tanggal ini.");
            return;
        }

        $start = Carbon::parse($schedule->start_time);
        $end = Carbon::parse($schedule->end_time);
        $endTimeExclusive = $end->copy()->subMinutes($this->slotDuration)->addMinute();

        $period = $start->greaterThan($end->copy()->subMinutes($this->slotDuration)) ? [] : CarbonPeriod::since($start)->minutes($this->slotDuration)->until($endTimeExclusive);

        $times = [];
        foreach ($period as $time) {
            $slotTime = $time->format('H:i');
            $slotDateTime = Carbon::parse($this->booking_date)->setTimeFromTimeString($slotTime);
            if ($slotDateTime->isFuture() || $slotDateTime->isSameMinute(Carbon::now())) {
                $times[] = $slotTime;
            }
        }

        $occupiedTimes = Booking::where('barber_id', $this->barber_id)
            ->where('booking_date', $this->booking_date)
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->pluck('booking_time')
            ->map(fn($time) => Carbon::parse($time)->format('H:i'))
            ->toArray();

        $this->availableTimes = array_values(array_diff($times, $occupiedTimes));

        if (!empty($times) && empty($this->availableTimes)) {
            session()->flash('alert_full', "Slot jam kerja Barber ini sudah terisi penuh pada tanggal " . Carbon::parse($this->booking_date)->translatedFormat('d F'));
        }
    }

    // Action save
    public function save()
    {
        $this->validate();

        if (!in_array($this->booking_time, $this->availableTimes)) {
            session()->flash('error', 'Slot waktu yang Anda pilih sudah terisi atau tidak tersedia lagi. Silakan pilih slot waktu lain!');
            return;
        }

        if ($this->totalPrice < 20000) {
            session()->flash('warning', 'Minimal total harga adalah Rp20.000');
            return;
        }

        $path = $this->proof_of_payment->store('proofs', 'public');

        try {
            $booking = Booking::create([
                'customer_id' => Auth::id(),
                'barber_id' => $this->barber_id,
                'barbershop_id' => $this->barbershop_id,
                'booking_date' => $this->booking_date,
                'booking_time' => $this->booking_time,
                'status' => 'pending',
                'proof_of_payment' => $path,
                'total_price' => $this->totalPrice,
            ]);

            $booking->services()->attach($this->selectedServices);

            session()->flash('success', 'Booking berhasil dibuat! Menunggu konfirmasi dari Barbershop');
            return $this->redirectRoute('customer.booking', navigate: true);

        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), 'Duplicate entry') 
                ? 'Slot waktu sudah dibooking oleh orang lain. Silakan pilih slot waktu lain!' 
                : 'Gagal menyimpan booking. Silakan coba lagi.' . (app()->environment('local') ? $e->getMessage() : '');
            session()->flash('error', $message);
            Log::error('Booking failed: ' . $e->getMessage());
        }
    }

    #[Layout('components.layouts.cust-app')]
    public function render()
    {
        return view('livewire.customer.cust-booking-create');
    }
}
