<div>
    <section class="bg-gray-50 dark:bg-gray-900 pt-16 md:pt-24 pb-10 min-h-screen">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">

            {{-- SUCCESS / INFO / ERROR ALERT --}}
            @if (session()->has('success') || session()->has('error') || session()->has('info') || session()->has('warning'))
                @php
                    $type = 'info';
                    if (session()->has('success')) {
                        $type = 'success';
                    } elseif (session()->has('error')) {
                        $type = 'error';
                    } elseif (session()->has('warning')) {
                        $type = 'warning';
                    }

                    $color = match ($type) {
                        'success' => 'green',
                        'error' => 'red',
                        'warning' => 'yellow',
                        default => 'blue',
                    };
                    $message = session($type);
                @endphp

                <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-[-10px]"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-[-10px]" x-init="setTimeout(() => show = false, 3000)" class="relative mb-4">

                    <div class="flex items-center p-4 text-sm font-semibold border rounded-lg shadow-md
                        text-{{ $color }}-800 border-{{ $color }}-400 bg-{{ $color }}-100 
                        dark:bg-{{ $color }}-900 dark:text-{{ $color }}-300"
                        role="alert">
                        <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            @if ($type === 'success')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856a2 2 0 001.789-2.858L13.789 4.142a2 2 0 00-3.578 0L3.35 16.142A2 2 0 005.142 19z" />
                            @endif
                        </svg>
                        <div>
                            {!! $message !!} {{-- Menggunakan {!! !!} untuk mengizinkan formatting markdown/HTML di pesan flash --}}
                        </div>
                    </div>
                </div>
            @endif
            {{-- AKHIR ALERT --}}

            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

                {{-- Pencarian & tombol --}}
                <div
                    class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <div class="relative w-full" wire:loading.class="opacity-50" wire:target="query">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="query"
                                placeholder="Cari berdasarkan nama atau barbershop..."
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500">
                        </div>
                    </div>

                    <div
                        class="w-full md:w-auto flex flex-col md:flex-row items-stretch justify-end space-y-2 md:space-y-0 md:space-x-3 flex-shrink-0">
                        {{-- PERBAIKAN: Mengganti route ke yang benar untuk CREATE --}}
                        <a wire:navigate href="{{ route('customer.bookingCreate') }}"
                            class="flex items-center justify-center text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-yellow-500 dark:hover:bg-yellow-600 focus:outline-none dark:focus:ring-yellow-800 transition">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Buat Booking Baru
                        </a>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">

                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">Barber</th>
                                <th scope="col" class="px-4 py-3">Barbershop</th>
                                <th scope="col" class="px-4 py-3">Layanan</th>
                                <th scope="col" class="px-4 py-3">Tanggal & Jam</th>
                                <th scope="col" class="px-4 py-3">Total Harga</th>
                                <th scope="col" class="px-4 py-3">Status</th>
                                <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-4 py-3">{{ $booking->barber->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">{{ $booking->barbershop->name ?? 'N/A' }}</td>

                                    <td class="px-4 py-3">
                                        {{ $booking->services->pluck('name')->implode(', ') }}
                                        @if ($booking->total_price)
                                            <span class="text-xs block text-gray-400">({{ count($booking->services) }}
                                                layanan)</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}
                                        <span class="text-xs font-semibold block text-gray-500 dark:text-gray-400">
                                            Pukul: {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">
                                        Rp{{ number_format($booking->total_price ?? 0, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-3 capitalize">
                                        {{-- PERBAIKAN: Menambahkan pending_verification --}}
                                        @php
                                            $status = $booking->status;
                                            $colorClass = match ($status) {
                                                'pending'
                                                    => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                                'confirmed'
                                                    => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                'completed'
                                                    => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                                'cancelled'
                                                    => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                                default
                                                    => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                            };
                                        @endphp
                                        <span class="{{ $colorClass }} px-2 py-1 rounded-full text-xs font-medium">
                                            {{ str_replace('_', ' ', $status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 flex items-center justify-end gap-2">
                                        <a wire:navigate href="{{ route('customer.bookingDetail', $booking->id) }}"
                                            class="focus:outline-none text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-xs px-3 py-2 dark:focus:ring-green-900 transition">Detail</a>

                                        {{-- PERBAIKAN: Membolehkan pembatalan jika statusnya pending, pending_verification, atau confirmed --}}
                                        @if (in_array($booking->status, ['pending', 'confirmed']))
                                            <button wire:click="confirmCancel({{ $booking->id }})"
                                                class="focus:outline-none text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-400 font-medium rounded-lg text-xs px-3 py-2 dark:focus:ring-red-900 transition">Batalkan</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center px-4 py-12 text-gray-500 dark:text-gray-400">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <p class="mt-2 text-lg font-medium">Tidak ada booking yang ditemukan.</p>
                                        <p class="text-sm">Coba ubah kata kunci pencarian atau buat booking baru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-4">
                    {{ $bookings->links() }}
                </div>

            </div>
        </div>
    </section>


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
                        <span wire:loading wire:target="cancelBooking">Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
