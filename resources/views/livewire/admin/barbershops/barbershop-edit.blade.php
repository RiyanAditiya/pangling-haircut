<div>
    <flux:heading size="xl" level="1">{{ __('Edit Barbershop') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">
        {{ __('Ubah data barbershop Pangling Haircut') }}
    </flux:subheading>
    <flux:separator variant="subtle" />

    <section class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 mt-6">
        <form wire:submit.prevent="update" class="space-y-5">

            <!-- Nama -->
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                <input type="text" id="name" wire:model="name"
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 
                              dark:bg-gray-700 dark:border-gray-600 dark:text-white 
                              focus:ring-primary-500 focus:border-primary-500">
                @error('name')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Address -->
            <div>
                <label for="address"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat</label>
                <textarea id="address" wire:model="address" rows="3"
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900
               dark:bg-gray-700 dark:border-gray-600 dark:text-white 
               focus:ring-primary-500 focus:border-primary-500"></textarea>
                @error('address')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor
                    Telepon</label>
                <input type="tel" id="phone" wire:model="phone"
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900
               dark:bg-gray-700 dark:border-gray-600 dark:text-white 
               focus:ring-primary-500 focus:border-primary-500">
                @error('phone')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit -->
            <div class="pt-4 flex justify-end gap-2">
                <!-- Tombol Kembali -->
                <a wire:navigate href="{{ route('admin.barbershop') }}"
                    class="inline-flex justify-center items-center px-5 py-2.5 
               text-sm font-medium text-white bg-gray-500 rounded-lg 
               hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 
               dark:focus:ring-gray-700">
                    Kembali
                </a>

                <!-- Tombol Update -->
                <button type="submit"
                    class="inline-flex justify-center items-center px-5 py-2.5 
               text-sm font-medium text-white bg-yellow-600 rounded-lg 
               hover:bg-yellow-700 focus:ring-4 focus:ring-yellow-200 
               dark:focus:ring-yellow-900">
                    Update Barbershop
                </button>

            </div>

        </form>
    </section>
</div>
