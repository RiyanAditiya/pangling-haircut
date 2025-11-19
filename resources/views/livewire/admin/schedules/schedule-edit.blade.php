<div>
    <flux:heading size="xl" level="1">{{ __('Edit Jadwal') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">
        {{ __('Edit jadwal barber Pangling Haircut') }}
    </flux:subheading>
    <flux:separator variant="subtle" />

    <section class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 mt-6">
        <form wire:submit.prevent="update" class="space-y-5" x-data="{ isDayOff: @entangle('is_day_off') }">

            <!-- Hari -->
            <div>
                <label for="day_of_week" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Hari
                </label>
                <select id="day_of_week" wire:model="day_of_week"
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900
                           dark:bg-gray-700 dark:border-gray-600 dark:text-white
                           focus:ring-primary-500 focus:border-primary-500">
                    <option value="">-- Pilih Hari --</option>
                    @foreach ($days as $day)
                        <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                    @endforeach
                </select>
                @error('day_of_week')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Hari Libur -->
            <div class="flex items-center gap-2">
                <input id="is_day_off" type="checkbox" x-model="isDayOff" wire:model="is_day_off"
                    class="w-4 h-4 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500">
                <label for="is_day_off" class="text-sm font-medium text-gray-900 dark:text-white">
                    Tandai sebagai Hari Libur
                </label>
            </div>

            <!-- Barber -->
            <div>
                <label for="barber_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Nama Barber
                </label>
                <select id="barber_id" wire:model="barber_id"
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900
                           dark:bg-gray-700 dark:border-gray-600 dark:text-white
                           focus:ring-primary-500 focus:border-primary-500">
                    <option value="">-- Pilih Barber --</option>
                    @foreach ($barbers as $barber)
                        <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                    @endforeach
                </select>
                @error('barber_id')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Barbershop + Jam hanya muncul kalau bukan hari libur -->
            <div x-show="!isDayOff" x-transition class="space-y-5">

                <!-- Barbershop -->
                <div>
                    <label for="barbershop_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Barbershop
                    </label>
                    <select id="barbershop_id" wire:model="barbershop_id"
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white
                               focus:ring-primary-500 focus:border-primary-500">
                        <option value="">-- Pilih Barbershop --</option>
                        @foreach ($barbershops as $shop)
                            <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                        @endforeach
                    </select>
                    @error('barbershop_id')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Jam Mulai & Selesai -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-900 dark:text-white">
                            Jam Mulai
                        </label>
                        <input type="time" id="start_time" wire:model="start_time"
                            class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900
                                   dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                   focus:ring-primary-500 focus:border-primary-500">
                        @error('start_time')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-900 dark:text-white">
                            Jam Selesai
                        </label>
                        <input type="time" id="end_time" wire:model="end_time"
                            class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900
                                   dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                   focus:ring-primary-500 focus:border-primary-500">
                        @error('end_time')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Slot Duration -->
                <div>
                    <label for="slot_duration" class="block text-sm font-medium text-gray-900 dark:text-white">
                        Durasi Slot (menit)
                    </label>
                    <input type="number" id="slot_duration" wire:model="slot_duration" min="15" step="15"
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white
                               focus:ring-primary-500 focus:border-primary-500">
                    @error('slot_duration')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <!-- Submit -->
            <div class="pt-4 flex justify-end gap-2">
                <a wire:navigate href="{{ route('admin.schedule') }}"
                    class="inline-flex justify-center items-center px-5 py-2.5 text-sm font-medium text-white
                           bg-gray-500 rounded-lg hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700">
                    Kembali
                </a>
                <button type="submit"
                    class="inline-flex justify-center items-center px-5 py-2.5 text-sm font-medium text-white
                           bg-yellow-600 rounded-lg hover:bg-yellow-700 focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900">
                    Simpan
                </button>
            </div>

        </form>
    </section>
</div>
