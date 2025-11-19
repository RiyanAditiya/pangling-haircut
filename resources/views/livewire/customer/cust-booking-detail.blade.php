<div x-data="{ showProof: false, showCancellationModal: @json($showCancellationModal ?? false) }" class="bg-gray-50 dark:bg-gray-900 min-h-screen pt-12 sm:pt-16 lg:pt-20 pb-10 font-inter">

    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 mt-8 sm:mt-12">

        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-8 sm:mb-10 gap-4 sm:gap-0">
            <a wire:navigate href="{{ route('customer.booking') }}"
                class="inline-flex items-center justify-center px-5 py-2 border border-gray-300 dark:border-gray-600 rounded-xl shadow-md text-sm font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-200 transform hover:scale-[1.02]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white text-center sm:text-right">
                Booking ID: <span class="text-yellow-600 dark:text-yellow-400">#{{ $booking->id }}</span>
            </h1>
        </div>

        <div
            class="bg-white dark:bg-gray-800 shadow-3xl rounded-3xl overflow-hidden divide-y divide-gray-200 dark:divide-gray-700 text-sm border border-gray-100 dark:border-gray-700/50">

            <div
                class="p-5 sm:p-7 flex flex-col sm:flex-row justify-between sm:items-center gap-3 sm:gap-0 bg-yellow-50 dark:bg-gray-700/70">
                <div>
                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1">
                        Status Booking</p>
                    @php
                        $status = $booking->status;
                        $colorClass = match ($status) {
                            'pending'
                                => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 ring-yellow-500',
                            'confirmed'
                                => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 ring-green-500',
                            'completed'
                                => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300 ring-indigo-500',
                            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 ring-red-500',
                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 ring-gray-500',
                        };
                        $icon = match ($status) {
                            'pending'
                                => '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                            'confirmed'
                                => '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                            'completed'
                                => '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>',
                            'cancelled'
                                => '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>',
                            default
                                => '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                        };
                    @endphp
                    <span
                        class="mt-1 inline-flex items-center px-4 py-1.5 text-sm font-extrabold uppercase rounded-full ring-2 ring-opacity-50 {{ $colorClass }}">
                        {!! $icon !!}
                        {{ str_replace('_', ' ', $status) }}
                    </span>
                </div>
                <div class="text-left sm:text-right">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Tanggal: <span
                            class="text-gray-900 dark:text-white font-bold">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}</span>
                    </p>
                    <p class="text-2xl font-extrabold text-yellow-700 dark:text-yellow-400 mt-1">
                        Pukul: {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}
                    </p>
                </div>
            </div>

            {{-- Detail Lokasi & Barber --}}
            <div class="px-5 sm:px-7 py-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Detail Lokasi & Layanan
                </h3>

                {{-- Flex kiri-kanan untuk barber dan barbershop --}}
                <div
                    class="flex flex-col sm:flex-row justify-between gap-6 sm:gap-12 text-left border-t border-gray-200 dark:border-gray-700 pt-5">
                    {{-- Barber kiri --}}
                    <div class="sm:w-1/2">
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Barber
                            Pilihan</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $booking->barber->name ?? 'N/A' }}
                        </dd>
                    </div>

                    {{-- Barbershop kanan --}}
                    <div class="sm:w-1/2 sm:text-right">
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lokasi
                            Barbershop</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                            {{ $booking->barbershop->name ?? 'N/A' }}
                        </dd>
                        <dd class="mt-1 text-xs text-gray-500 dark:text-gray-400 italic">
                            {{ $booking->barbershop->address ?? '' }}
                        </dd>
                    </div>
                </div>
            </div>

            <div class="px-5 sm:px-7 py-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1v-3m0 0l2 2m-2-2l-2-2m2 2l4-4m-4 4V8a4 4 0 00-4-4H5a4 4 0 00-4 4v12a4 4 0 004 4h9a4 4 0 004-4v-1" />
                    </svg>
                    Layanan yang Dipilih
                </h3>
                <ul
                    class="divide-y divide-gray-200 dark:divide-gray-700 border border-gray-200 dark:border-gray-700 rounded-xl shadow-inner">
                    @forelse ($booking->services as $service)
                        <li
                            class="px-4 py-3 flex justify-between items-center bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                            <span
                                class="text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $service->name }}</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                Rp{{ number_format($service->price, 0, ',', '.') }}
                            </span>
                        </li>
                    @empty
                        <li
                            class="px-4 py-3 text-center text-gray-500 dark:text-gray-400 italic bg-white dark:bg-gray-800">
                            Tidak ada layanan dipilih
                        </li>
                    @endforelse
                </ul>
            </div>

            <div
                class="px-5 sm:px-7 py-5 bg-gray-100 dark:bg-gray-700/50 border-t border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-extrabold text-gray-900 dark:text-white">Total Pembayaran</h3>
                    <span class="text-3xl font-extrabold text-yellow-700 dark:text-yellow-400">
                        Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="p-5 sm:p-7 bg-white dark:bg-gray-800 flex flex-col gap-6">

                {{-- Bukti Pembayaran --}}
                <div class="mt-2">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 flex items-center">
                        <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Bukti Pembayaran
                    </h3>

                    @if ($booking->proof_of_payment)
                        <div
                            class="flex flex-col items-center justify-center border border-dashed border-gray-300 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900/40 p-6 text-center shadow-inner">
                            <img src="{{ asset('storage/' . $booking->proof_of_payment) }}" alt="Bukti Pembayaran"
                                @click="showProof = true"
                                class="w-48 sm:w-64 rounded-lg object-cover ring-2 ring-gray-200 dark:ring-gray-700 mb-4 cursor-pointer hover:opacity-80 transition duration-300">
                            <button @click="showProof = true"
                                class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-lg transition transform hover:scale-[1.03]">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Perbesar Bukti
                            </button>
                        </div>
                    @else
                        <div
                            class="border border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-6 text-center bg-gray-50 dark:bg-gray-900/40 shadow-inner">
                            <p class="text-sm text-gray-600 dark:text-gray-400 italic font-medium">
                                Belum ada bukti pembayaran yang diunggah. Silakan unggah untuk verifikasi.
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Tombol Batalkan Booking --}}


                @if (in_array($booking->status, ['pending', 'pending_verification', 'confirmed']))
                    <div class="flex justify-center mt-4 pt-5 border-t border-gray-100 dark:border-gray-700">
                        <button wire:click="confirmCancel({{ $booking->id }})"
                            class="px-6 py-2.5 border border-red-500 text-red-600 dark:text-red-400 rounded-xl font-bold hover:bg-red-50 dark:hover:bg-red-900 transition text-base flex items-center gap-2 shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Batalkan Booking
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div x-show="showProof" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4" @click.self="showProof = false">
        <div class="relative max-w-3xl w-full">
            <img src="{{ asset('storage/' . $booking->proof_of_payment) }}" alt="Bukti Pembayaran Zoom"
                class="rounded-xl shadow-2xl w-full object-contain max-h-[90vh]">
            <button @click="showProof = false"
                class="absolute top-4 right-4 bg-white/90 dark:bg-gray-800/90 text-gray-800 dark:text-white p-3 rounded-full shadow-lg hover:bg-white transition text-lg font-bold">
                ✕
            </button>
        </div>
    </div>

    {{-- Modal Cancel --}}
    @if ($cancelledId)
        <div class="fixed inset-0 bg-black/60 dark:bg-black/80 flex items-center justify-center z-50 p-4"
            x-data="{ open: @entangle('cancelledId') }" x-show="open" x-transition>
            <div class="bg-white dark:bg-gray-700 rounded-xl shadow-2xl w-full max-w-sm p-6"
                @click.away="$wire.set('cancelledId', null)">
                <h2 class="text-xl font-bold text-red-600 dark:text-red-400">Konfirmasi Pembatalan</h2>
                <p class="mt-3 text-gray-600 dark:text-gray-300">Apakah Anda yakin ingin membatalkan booking ini?
                    Tindakan ini tidak dapat dibatalkan.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="$set('cancelledId', null)"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition">Tutup</button>
                    <button wire:click="cancelBooking"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                        wire:loading.attr="disabled" wire:target="cancelBooking">
                        <span wire:loading.remove wire:target="cancelBooking">Ya, Batalkan</span>
                        <svg wire:loading wire:target="cancelBooking" class="animate-spin h-5 w-5 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
