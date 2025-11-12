<div class="max-w-2xl mx-auto bg-white dark:bg-gray-900 p-8 sm:p-10 rounded-2xl shadow-xl mt-12 sm:mt-16 mb-12">
    <h2 class="text-3xl font-extrabold mb-8 text-gray-800 dark:text-gray-100 text-center">
        Buat Booking Baru
    </h2>

    {{-- Alert untuk Warning saja --}}
    @if (session()->has('warning') || session()->has('alert_full'))
        @php
            $message = session('warning') ?? session('alert_full');
        @endphp
        <div class="mb-6 p-4 rounded-lg border bg-yellow-100 border-yellow-400 text-yellow-800 shadow-md">
            <p class="font-semibold">Warning</p>
            <p class="text-sm">{!! nl2br($message) !!}</p>
        </div>
    @endif

    {{-- FORM BOOKING --}}
    <form wire:submit.prevent="save" class="space-y-6" enctype="multipart/form-data">

        {{-- Barbershop --}}
        <div>
            <label for="barbershop_id" class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
                Barbershop
            </label>
            <select wire:model.live="barbershop_id" id="barbershop_id"
                class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-yellow-400 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('barbershop_id') border-red-500 @enderror">
                <option value="">-- pilih barbershop --</option>
                @foreach ($barbershops as $shop)
                    <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                @endforeach
            </select>
            @error('barbershop_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tanggal Booking --}}
        <div>
            <label for="booking_date" class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
                Tanggal Booking
            </label>
            <input type="date" wire:model.live="booking_date" id="booking_date"
                min="{{ \Carbon\Carbon::today()->toDateString() }}"
                class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-yellow-400 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('booking_date') border-red-500 @enderror">
            @error('booking_date')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Pilih Layanan --}}
        <div>
            <label class="block text-sm font-semibold mb-3 text-gray-700 dark:text-gray-300">Pilih Layanan</label>
            <div class="space-y-2 max-h-48 overflow-y-auto p-2 border rounded-lg dark:border-gray-700">
                @forelse ($services as $service)
                    <div
                        class="flex items-center justify-between p-2 rounded-md hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <label class="flex items-center cursor-pointer flex-1">
                            <input type="checkbox" wire:model.live="selectedServices" value="{{ $service->id }}"
                                class="mr-3 text-yellow-500 focus:ring-yellow-500 rounded">
                            <span class="text-gray-800 dark:text-gray-200">{{ $service->name }}</span>
                        </label>
                        <span class="font-medium text-gray-600 dark:text-gray-400">
                            Rp{{ number_format($service->price ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400">Tidak ada layanan tersedia.</p>
                @endforelse
            </div>
            @error('selectedServices')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            <div class="mt-4 p-3 bg-yellow-50 dark:bg-gray-800 rounded-lg font-bold text-lg flex justify-between">
                <span>Total Harga:</span>
                <span class="text-yellow-600 dark:text-yellow-400">
                    Rp{{ number_format($totalPrice, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <hr class="dark:border-gray-700">

        {{-- Pilih Barber --}}
        <div wire:loading.class="opacity-50" wire:target="barbershop_id, booking_date">
            <label for="barber_id" class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
                Pilih Barber
            </label>
            <select wire:model.live="barber_id" id="barber_id"
                class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-yellow-400 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('barber_id') border-red-500 @enderror"
                {{ empty($barbershop_id) || empty($booking_date) || empty($barbers) ? 'disabled' : '' }}>
                <option value="">-- pilih barber --</option>
                @forelse ($barbers as $barber)
                    <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                @empty
                    <option value="" disabled>-- Tidak ada barber tersedia --</option>
                @endforelse
            </select>
            @error('barber_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Pilih Jam --}}
        <div wire:loading.class="opacity-50" wire:target="barber_id">
            <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
                Pilih Jam Booking
            </label>
            @if (!empty($availableTimes))
                <div class="grid grid-cols-3 sm:grid-cols-5 gap-3">
                    @foreach ($availableTimes as $time)
                        @php
                            $isSelected = $booking_time == $time;
                            $buttonClass = $isSelected
                                ? 'bg-yellow-600 text-white shadow-md border-yellow-600 hover:bg-yellow-700'
                                : 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 dark:border-gray-600';
                        @endphp
                        <button type="button" wire:click.prevent="$set('booking_time', '{{ $time }}')"
                            role="radio" aria-checked="{{ $isSelected ? 'true' : 'false' }}"
                            class="p-2 rounded-lg text-sm border font-medium transition duration-150 ease-in-out {{ $buttonClass }}">
                            {{ $time }}
                        </button>
                    @endforeach
                </div>
            @else
                <p
                    class="text-sm text-gray-500 dark:text-gray-400 mt-2 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border dark:border-gray-700">
                    @if ($barber_id)
                        Slot waktu tidak tersedia atau sudah terisi penuh.
                    @else
                        Pilih Barbershop, Tanggal, dan Barber untuk melihat slot waktu tersedia.
                    @endif
                </p>
            @endif
            @error('booking_time')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Bukti Transfer --}}
        <div>
            <label for="proof_of_payment" class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
                Upload Bukti Transfer <span class="text-red-500">*</span>
            </label>
            <input type="file" wire:model="proof_of_payment" id="proof_of_payment" accept="image/*"
                class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-yellow-400 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('proof_of_payment') border-red-500 @enderror">
            @error('proof_of_payment')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            {{-- Pengingat Transfer --}}
            <div
                class="mt-3 p-2.5 bg-yellow-50 dark:bg-gray-800 border border-yellow-200 dark:border-gray-700 rounded-md flex items-start gap-2 shadow-sm text-xs sm:text-[13px]">

                {{-- Icon --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-yellow-500 mt-0.5 flex-shrink-0"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20 10 10 0 010-20z" />
                </svg>

                {{-- Text --}}
                <div class="flex-1 text-yellow-700 dark:text-yellow-300 leading-snug">
                    <p class="mb-1">
                        Minimal transfer <span class="font-semibold">Rp20.000</span>. Harap unggah bukti pembayaran
                        yang jelas agar booking segera diproses.
                    </p>

                    {{-- Rekening --}}
                    <div
                        class="mt-1 bg-white/70 dark:bg-gray-900/50 border border-yellow-200 dark:border-gray-700 rounded p-1.5 text-[12px] text-gray-800 dark:text-gray-200">
                        <p class="font-semibold text-gray-900 dark:text-yellow-300">Rekening: BCA</p>
                        <p><span class="font-semibold">1234567890</span> <span
                                class="text-gray-600 dark:text-gray-400 italic">a.n. Wawan</span></p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Tombol --}}
        <div class="flex flex-col sm:flex-row gap-3 pt-4">
            <a wire:navigate href="{{ route('customer.booking') }}"
                class="flex-1 py-3 text-center px-4 rounded-lg shadow text-sm font-semibold bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition">
                Kembali
            </a>
            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-wait"
                wire:target="save, proof_of_payment"
                class="flex-1 py-3 px-4 rounded-lg shadow text-sm font-semibold transition
                {{ $booking_time && $barber_id && count($selectedServices) > 0 && $totalPrice >= 20000 && $proof_of_payment ? 'bg-yellow-600 text-white hover:bg-yellow-700' : 'bg-gray-400 text-gray-600 cursor-not-allowed dark:bg-gray-600 dark:text-gray-400' }}"
                {{ !$booking_time || !$barber_id || count($selectedServices) === 0 || $totalPrice < 20000 || !$proof_of_payment ? 'disabled' : '' }}>
                <span wire:loading.remove wire:target="save">Booking Sekarang
                    (Rp{{ number_format($totalPrice, 0, ',', '.') }})</span>
                <span wire:loading wire:target="save">Memproses Booking...</span>
            </button>
        </div>
    </form>
</div>
