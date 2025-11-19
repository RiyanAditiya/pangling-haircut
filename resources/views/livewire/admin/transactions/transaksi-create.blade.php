<div>

    <flux:heading size="xl" level="1">{{ __('Pencatatan Transaksi') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">
        {{ __('Input data transaksi untuk customer Pangling Haircut') }}
    </flux:subheading>
    <flux:separator variant="subtle" />

    <section class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 mt-6">
        <form wire:submit.prevent="saveTransaction" class="space-y-6">

            <!-- 1. Nama Customer -->
            <div>
                <label for="customer_name_manual"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Customer</label>
                <input type="text" id="customer_name_manual" wire:model.defer="customer_name_manual"
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 
                            dark:bg-gray-700 dark:border-gray-600 dark:text-white 
                            focus:ring-yellow-500 focus:border-yellow-500"
                    placeholder="Contoh: Budi Santoso (Wajib diisi)">
                @error('customer_name_manual')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- 2. Pilihan Barbershop -->
            <div>
                <label for="barbershop_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tempat
                    Barbershop</label>
                <select id="barbershop_id" wire:model.defer="barbershop_id"
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 
                            dark:bg-gray-700 dark:border-gray-600 dark:text-white 
                            focus:ring-yellow-500 focus:border-yellow-500">
                    <option value="">-- Pilih Barbershop --</option>
                    @foreach ($barbershops as $shop)
                        <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                    @endforeach
                </select>
                @error('barbershop_id')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- 3. Nama Barber (Diasumsikan staf yang login) -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Nama Barber
                </label>
                <input type="text" value="{{ auth()->user()->name ?? 'N/A' }}" readonly
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 
                            dark:bg-gray-700 dark:border-gray-600 dark:text-white 
                            bg-gray-100 dark:bg-gray-600 cursor-not-allowed" />
                <!-- Penting: Pastikan properti $barber_id di Livewire Class di-set ke ID user yang sedang login -->
            </div>


            <!-- 4. Pilihan Layanan -->
            <div class="p-4 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-800">
                <label class="block mb-3 text-sm font-bold text-gray-900 dark:text-white">Pilih Layanan Yang
                    Diambil</label>
                <div class="grid grid-cols-2 gap-3">
                    @foreach ($allServices as $service)
                        <label class="flex items-center space-x-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            <input type="checkbox" wire:model.live="selected_service_ids" value="{{ $service->id }}"
                                class="rounded border-gray-400 text-yellow-600 shadow-sm focus:ring-yellow-500">
                            <span>{{ $service->name }} <span class="text-yellow-600 font-semibold">(Rp
                                    {{ number_format($service->price) }})</span></span>
                        </label>
                    @endforeach
                </div>
                @error('selected_service_ids')
                    <span class="text-red-500 text-xs mt-2">{{ $message }}</span>
                @enderror
            </div>

            <!-- 5. Total Bayar (Otomatis) -->
            <div>
                <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total Bayar
                    (Otomatis)</label>
                <input type="text" id="amount" value="Rp {{ number_format($amount ?? 0) }}" readonly
                    class="w-full rounded-lg border-2 border-yellow-500 bg-yellow-50 p-2.5 text-lg font-extrabold text-yellow-700 
                            dark:bg-gray-700 dark:border-yellow-600 dark:text-white">
                <input type="hidden" wire:model="amount">
                @error('amount')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>


            <div class="pt-4 flex justify-end gap-2">
                <a wire:navigate href="{{ route('staff.transactions') }}"
                    class="inline-flex justify-center items-center px-5 py-2.5 text-sm font-medium text-white
                            bg-gray-500 rounded-lg hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700">
                    Kembali
                </a>

                <!-- LOGIKA PERBAIKAN TOMBOL SIMPAN -->
                <button type="submit"
                    class="inline-flex justify-center items-center px-5 py-2.5 text-sm font-medium text-white transition duration-150 ease-in-out
                            bg-yellow-600 rounded-lg hover:bg-yellow-700 focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900
                            {{ ($amount ?? 0) <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                    wire:loading.attr="disabled" {{ ($amount ?? 0) <= 0 ? 'disabled' : '' }}>
                    <span wire:loading.remove wire:target="saveTransaction">Simpan Transaksi</span>
                    <span wire:loading wire:target="saveTransaction">Memproses...</span>
                </button>
                <!-- AKHIR LOGIKA PERBAIKAN -->
            </div>

        </form>
    </section>
</div>
