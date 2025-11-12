<div>

    {{-- Success Alert (di luar container utama) --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-5 right-5 flex items-center gap-3 bg-green-500 text-white px-5 py-3 rounded-lg shadow-lg z-[9999]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif


    <div class="relative mb-6 w-full">

        {{-- Heading --}}
        <flux:heading size="xl" level="1">{{ __('Jadwal Barber') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Kelola jadwal barber Pangling Haircut') }}
        </flux:subheading>
        <flux:separator variant="subtle" />

        {{-- Search & Add --}}
        <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden mt-5 mb-2">
            <div class="flex flex-col md:flex-row items-center justify-between p-4 space-y-3 md:space-y-0 md:space-x-4">

                {{-- Search --}}
                <div class="w-full md:w-1/2">
                    <form wire:submit.prevent class="flex items-center">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input wire:model.live.debounce.250ms="query" type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Cari jadwal berdasarkan nama dan hari...">
                        </div>
                    </form>
                </div>

                {{-- Tambah Jadwal --}}
                <div class="w-full md:w-auto flex items-center justify-end">
                    <a wire:navigate href="{{ route('admin.scheduleCreate') }}"
                        class="flex items-center justify-center text-white bg-yellow-600 hover:bg-yellow-700 font-medium rounded-lg text-sm px-4 py-2">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Tambah Jadwal
                    </a>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-4">Nama Barber</th>
                            <th class="px-4 py-3">Barbershop</th>
                            <th class="px-4 py-3">Hari</th>
                            <th class="px-4 py-3">Jam Mulai</th>
                            <th class="px-4 py-3">Jam Selesai</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schedules as $schedule)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">{{ $schedule->barber->name }}</td>
                                <td class="px-4 py-3">{{ $schedule->barbershop->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    {{ __(ucfirst($schedule->day_of_week)) }}
                                    @if ($schedule->is_day_off)
                                        <span class="text-red-500 font-semibold">(Libur)</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    {{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '-' }}
                                </td>
                                <td class="px-4 py-3 flex justify-end gap-2">
                                    <a wire:navigate href="{{ route('admin.scheduleEdit', $schedule->id) }}"
                                        class="px-3 py-2 text-xs text-white bg-yellow-400 hover:bg-yellow-500 rounded-lg">Edit</a>
                                    <button wire:click="confirmDelete({{ $schedule->id }})"
                                        class="px-3 py-2 text-xs text-white bg-red-500 hover:bg-red-600 rounded-lg">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                    <svg class="mx-auto w-16 h-16 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.75 9.75L14.25 14.25M14.25 9.75L9.75 14.25M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <h3 class="mt-4 text-lg font-semibold">Data tidak ditemukan</h3>
                                    <p class="mt-2 text-sm">Coba masukkan kata kunci lain atau periksa kembali pencarian
                                        Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4">
                {{ $schedules->links() }}
            </div>
        </div>

        {{-- Modal Delete --}}
        @if ($deleteId)
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg w-96 p-6">
                    <h2 class="text-lg font-semibold text-gray-800">Konfirmasi Hapus</h2>
                    <p class="mt-2 text-gray-600">Apakah Anda yakin ingin menghapus jadwal ini?</p>
                    <div class="mt-4 flex justify-end gap-3">
                        <button wire:click="$set('deleteId', null)"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Batal</button>
                        <button wire:click="delete"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
