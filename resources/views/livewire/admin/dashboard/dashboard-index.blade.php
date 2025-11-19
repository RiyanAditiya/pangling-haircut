<div class="relative mb-8 w-full space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            Dashboard Pangling Haircut
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ now()->translatedFormat('l, d F Y') }}
        </p>
    </div>

    {{-- Statistik Ringkas --}}
    <div class="grid gap-4 md:grid-cols-3">

        {{-- Card 1: Total Booking Hari Ini --}}
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-5 rounded-2xl shadow-md">
            <h2 class="text-sm font-medium opacity-90">Total Booking Hari Ini</h2>
            <p class="text-3xl font-bold mt-2" wire:poll.10s="loadStats">
                {{ $totalBookingsToday }}
            </p>
            <span class="text-xs opacity-75">Update setiap 10 detik</span>
        </div>

        {{-- Card 2: Total Transaksi Hari ini --}}
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white p-5 rounded-2xl shadow-md">
            <h2 class="text-sm font-medium opacity-90">Total Transaksi Hari ini (Walk-in + Booking)</h2>
            <p class="text-3xl font-bold mt-2" wire:poll.10s="loadStats">
                {{ $totalTransactionsToday }}
            </p>
            <span class="text-xs opacity-75">Update setiap 10 detik</span>
        </div>

        {{-- Card 3: Pendapatan Hari Ini --}}
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 text-white p-5 rounded-2xl shadow-md">
            <h2 class="text-sm font-medium opacity-90">Pendapatan Hari Ini</h2>
            <p class="text-3xl font-bold mt-2" wire:poll.10s="loadStats">
                Rp {{ number_format($totalRevenueToday, 0, ',', '.') }}
            </p>
            <span class="text-xs opacity-75">Update setiap 10 detik</span>
        </div>
    </div>

</div>
