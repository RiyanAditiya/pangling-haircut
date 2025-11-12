<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Laporan') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">
        {{ __('Lihat dan cetak laporan Pangling Haircut') }}
    </flux:subheading>
    <flux:separator variant="subtle" />

    <!-- Filter & Export Section -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg mb-6">
        <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">

            {{-- Input Tanggal Mulai --}}
            <div>
                <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal
                    Mulai</label>
                <input type="date" id="start_date" wire:model.live="startDate"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            {{-- Input Tanggal Akhir --}}
            <div>
                <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal
                    Akhir</label>
                <input type="date" id="end_date" wire:model.live="endDate"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            {{-- Tombol Export --}}
            <div class="md:col-span-2 flex flex-col sm:flex-row gap-3">
                <button wire:click="exportPdf"
                    class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900 transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export ke PDF
                </button>

                <button wire:click="exportExcel"
                    class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-900 transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export ke Excel
                </button>
            </div>
        </div>

        <!-- Ringkasan Statistik -->
        <div class="mt-6 pt-5 border-t border-gray-200 dark:border-gray-700 grid sm:grid-cols-2 gap-4">
            <div class="p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Total Transaksi</p>
                <p class="text-2xl font-bold text-indigo-800 dark:text-indigo-200">
                    {{ number_format($totalTransactions) }}
                </p>
            </div>

            <div class="p-4 bg-green-50 dark:bg-green-900/30 rounded-lg">
                <p class="text-sm font-medium text-green-600 dark:text-green-400">Total Pendapatan</p>
                <p class="text-2xl font-bold text-green-800 dark:text-green-200">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Tabel Data Transaksi -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Tanggal</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Customer</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Barbershop</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Layanan</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Barber</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Tipe</th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $transaction->transaction_date->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $transaction->customer->name ?? $transaction->customer_name_manual }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $transaction->barbershop->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $transaction->service_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $transaction->barber->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type == 'walkin' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right text-gray-900 dark:text-gray-100">
                                {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                Tidak ada data transaksi dalam rentang tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
