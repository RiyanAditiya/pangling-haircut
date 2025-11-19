<div class="relative mb-6 w-full">

    {{-- Success Alert --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-5 right-5 flex items-center gap-3 bg-green-500 text-white px-5 py-3 rounded-lg shadow-lg z-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <flux:heading size="xl" level="1">{{ __('Data Transaksi Barbershop') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">
        {{ __('Daftar semua transaksi yang tercatat, baik dari booking yang selesai maupun walk-in.') }}
    </flux:subheading>
    <flux:separator variant="subtle" />

    <!-- Start coding here -->
    <div>
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden mt-5 mb-2">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">

                {{-- SEARCH --}}
                <div class="w-full md:w-1/2">
                    <form wire:submit="search" class="flex items-center">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                    fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <!-- wire:model.live.debounce.250ms="query" -->
                            <input wire:model.live.debounce.250ms="query" type="text" id="simple-search"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                placeholder="Cari berdasarkan nama customer...">
                        </div>
                    </form>
                </div>

                {{-- ACTION BUTTON --}}
                <div
                    class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <!-- Pastikan route 'staff.transactionCreate' mengarah ke form Walk-in -->
                    <a wire:navigate href="{{ route('staff.transactionCreate') }}"
                        class="flex items-center justify-center text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-yellow-600 dark:hover:bg-yellow-700 focus:outline-none dark:focus:ring-yellow-800">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Transaksi Walk-in
                    </a>
                </div>
            </div>

            {{-- TABLE DATA TRANSAKSI --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-4">Tanggal</th>
                            <th scope="col" class="px-4 py-4">Jam Selesai</th>
                            <th scope="col" class="px-4 py-3">Barbershop</th>
                            <th scope="col" class="px-4 py-3">Customer</th>
                            <th scope="col" class="px-4 py-3">Barber</th>
                            <th scope="col" class="px-4 py-3">Layanan</th>
                            <th scope="col" class="px-4 py-3">Jenis</th>
                            <th scope="col" class="px-4 py-3">Jumlah (Rp)</th>
                            <th scope="col" class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Mengganti $barbershops menjadi $transactions -->
                        @forelse ($transactions as $transaction)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($transaction->transaction_date)->locale('id')->isoFormat('D MMM YYYY') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($transaction->transaction_date)->locale('id')->isoFormat('HH:mm') }}
                                </td>
                                <!-- Asumsi ada relasi ke Barbershop -->
                                <td class="px-4 py-3">{{ $transaction->barbershop->name ?? 'N/A' }}</td>

                                <!-- Menampilkan nama customer (asumsi jika walk-in, namanya disimpan di kolom lain atau kita tampilkan ID) -->
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                    @if ($transaction->type == 'walkin')
                                        <!-- Jika walk-in, mungkin ada kolom customer_name_manual, atau kita gunakan ID/keterangan -->
                                        <span
                                            class="font-bold text-gray-600">{{ $transaction->customer_name_manual }}</span>
                                    @else
                                        {{ $transaction->customer->name ?? 'Customer (ID: ' . $transaction->customer_id . ')' }}
                                    @endif
                                </td>

                                <td class="px-4 py-3">{{ $transaction->barber->name ?? 'N/A' }}</td>

                                <!-- service_name adalah string gabungan -->
                                <td class="px-4 py-3 max-w-xs truncate">{{ $transaction->service_name }}</td>

                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-0.5 text-xs font-medium rounded {{ $transaction->type == 'booking' ? 'bg-yellow-100 text-yellow-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-semibold text-green-600 whitespace-nowrap">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 flex items-center justify-end">
                                    <button wire:click="confirmDelete({{ $transaction->id }})"
                                        class="focus:outline-none text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:ring-red-400 font-medium rounded-lg text-xs px-3 py-2 me-1 mb-2 dark:focus:ring-red-900">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center">
                                    <svg class="mx-auto w-16 h-16 text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.75 9.75L14.25 14.25M14.25 9.75L9.75 14.25M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <h3 class="mt-4 text-lg font-semibold text-gray-700 dark:text-gray-300">Belum ada
                                        data transaksi
                                        ditemukan</h3>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        Coba masukkan transaksi walk-in pertama Anda!
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
        <!-- Menampilkan link pagination -->
        {{ $transactions->links() }}
    </div>


    {{-- Modal Delete (Dapat disesuaikan jika ingin mengaktifkan hapus transaksi) --}}
    @if ($deleteId)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-96 p-6">
                <h2 class="text-lg font-semibold text-gray-800">Konfirmasi Hapus Transaksi</h2>
                <p class="mt-2 text-gray-600">Apakah Anda yakin ingin menghapus transaksi ini? Tindakan ini tidak dapat
                    dibatalkan.</p>
                <div class="mt-4 flex justify-end gap-3">
                    <button wire:click="$set('deleteId', null)"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                    <button wire:click="delete"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                </div>
            </div>
        </div>
    @endif
</div>
