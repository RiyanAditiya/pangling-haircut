<div class="relative mb-6 w-full">

    {{-- Success Alert --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-5 right-5 flex items-center gap-3 bg-green-500 text-white px-5 py-3 rounded-lg shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <flux:heading size="xl" level="1">{{ __('Data Booking') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Kelola data booking Pangling Haircut') }}
    </flux:subheading>
    <flux:separator variant="subtle" />

    <!-- Start coding here -->
    <div>
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden mt-5 mb-2">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-1/2">
                    <form wire:submit.prevent="search" class="flex items-center gap-2">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zM18 9H2v7a2 2 0 002 2h12a2 2 0 002-2V9z">
                                    </path>
                                </svg>
                            </div>
                            <input wire:model.live.debounce.250ms="searchDate" type="date" id="date-search"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500">
                        </div>

                        <button type="button" wire:click="resetSearch"
                            class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-lg transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                            Reset
                        </button>
                    </form>
                </div>
            </div>


            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Barbershop</th>
                            <th class="px-4 py-3">Layanan</th>
                            <th class="px-4 py-3">Tanggal & Jam</th>
                            <th class="px-4 py-3">Total Harga</th>
                            <th class="px-4 py-3">Bukti Transfer</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">{{ $booking->customer->name }}</td>
                                <td class="px-4 py-3">{{ $booking->barbershop->name }}</td>
                                <td class="px-4 py-3">
                                    {{ $booking->services->pluck('name')->implode(', ') }}
                                    <span class="text-xs block text-gray-400">({{ count($booking->services) }}
                                        layanan)</span>
                                </td>
                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}
                                    <span class="text-xs font-semibold block text-gray-500 dark:text-gray-400">
                                        Pukul: {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    Rp{{ number_format($booking->total_price ?? 0, 0, ',', '.') }}
                                </td>

                                {{-- ✅ Bukti Transfer --}}
                                <td class="px-4 py-3">
                                    @if ($booking->proof_of_payment)
                                        <button wire:click="showProof('{{ $booking->proof_of_payment }}')"
                                            class="text-blue-600 hover:underline text-sm">
                                            Lihat Bukti
                                        </button>
                                    @else
                                        <span class="text-gray-400 italic text-sm">Belum ada</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-3">
                                    @php
                                        $colorClass = match ($booking->status) {
                                            'pending'
                                                => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                            'confirmed'
                                                => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                            'completed'
                                                => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        };
                                    @endphp
                                    <span class="{{ $colorClass }} px-2 py-1 rounded-full text-xs font-medium">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>

                                {{-- ✅ Aksi --}}
                                <td class="px-4 py-3">
                                    <div class="flex justify-center items-center gap-1 h-full">
                                        @if ($booking->status === 'cancelled' || $booking->status === 'completed')
                                            <button disabled
                                                class="bg-gray-400 text-white text-[12px] px-2 py-1 rounded cursor-not-allowed opacity-70">
                                                Tidak Dapat Diubah
                                            </button>
                                        @else
                                            <button wire:click="openStatusModal({{ $booking->id }})"
                                                class="bg-yellow-500 text-white text-[12px] px-2 py-1 rounded hover:bg-yellow-600 transition">
                                                Ubah Status
                                            </button>
                                        @endif
                                    </div>
                                </td>


                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center">
                                    <svg class="mx-auto w-16 h-16 text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.75 9.75L14.25 14.25M14.25 9.75L9.75 14.25M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <h3 class="mt-4 text-lg font-semibold text-gray-700 dark:text-gray-300">Data tidak
                                        ditemukan</h3>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        Coba masukkan kata kunci lain atau periksa kembali pencarian Anda.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
        {{ $bookings->links() }}
    </div>

    {{-- ✅ Modal Bukti Transfer --}}
    @if ($proofImage)
        <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg p-4 max-w-lg w-full relative">
                <button wire:click="$set('proofImage', null)"
                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
                <h2 class="text-lg font-semibold mb-4">Bukti Transfer</h2>
                <img src="{{ asset('storage/' . $proofImage) }}" alt="Bukti Transfer"
                    class="w-full rounded-lg object-contain max-h-[70vh]">
            </div>
        </div>
    @endif

    {{-- ✅ Modal Ubah Status --}}
    @if ($statusModalId)
        {{-- MODAL OVERLAY --}}
        <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4" x-data>
            {{-- MODAL CONTAINER --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 w-full max-w-sm"
                @click.away="$wire.set('statusModalId', null)">

                <h2 class="text-xl font-bold mb-6 text-gray-800 dark:text-gray-100 text-center">
                    Ubah Status Booking
                </h2>

                {{-- ✅ Pilihan status (Menggunakan Radio Button agar hanya bisa pilih satu) --}}
                <div class="space-y-3">

                    {{-- Status Cancelled --}}
                    <label
                        class="flex items-center gap-4 p-3 border-2 {{ $newStatus === 'cancelled' ? 'border-red-500 bg-red-50 dark:bg-red-950' : 'border-gray-200 dark:border-gray-700' }} rounded-xl cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                        <input type="radio" value="cancelled" wire:model.live="newStatus" name="bookingStatus"
                            class="form-radio text-red-500 border-gray-300 focus:ring-red-500 w-5 h-5">
                        <span class="text-gray-800 dark:text-gray-100 font-medium">Cancelled (Dibatalkan)</span>
                    </label>

                    {{-- Status Confirmed --}}
                    <label
                        class="flex items-center gap-4 p-3 border-2 {{ $newStatus === 'confirmed' ? 'border-green-500 bg-green-50 dark:bg-green-950' : 'border-gray-200 dark:border-gray-700' }} rounded-xl cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                        <input type="radio" value="confirmed" wire:model.live="newStatus" name="bookingStatus"
                            class="form-radio text-green-500 border-gray-300 focus:ring-green-500 w-5 h-5">
                        <span class="text-gray-800 dark:text-gray-100 font-medium">Confirmed (Terkonfirmasi)</span>
                    </label>

                    {{-- Status Completed --}}
                    <label
                        class="flex items-center gap-4 p-3 border-2 {{ $newStatus === 'completed' ? 'border-blue-500 bg-blue-50 dark:bg-blue-950' : 'border-gray-200 dark:border-gray-700' }} rounded-xl cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                        <input type="radio" value="completed" wire:model.live="newStatus" name="bookingStatus"
                            class="form-radio text-blue-500 border-gray-300 focus:ring-blue-500 w-5 h-5">
                        <span class="text-gray-800 dark:text-gray-100 font-medium">Completed (Selesai)</span>
                    </label>
                </div>

                {{-- ✅ Tombol aksi --}}
                <div class="mt-8 flex justify-end gap-3">
                    <button wire:click="$set('statusModalId', null)" type="button"
                        class="px-5 py-2.5 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-xl hover:bg-gray-300 dark:hover:bg-gray-500 transition duration-150">
                        Batal
                    </button>
                    <button wire:click="updateStatus"
                        class="px-5 py-2.5 bg-yellow-600 text-white font-medium rounded-xl hover:bg-yellow-700 transition duration-150">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    @endif


</div>
