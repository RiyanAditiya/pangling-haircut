<div>
    <flux:heading size="xl" level="1">{{ __('Tambah Layanan') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">
        {{ __('Tambah layanan Pangling Haircut') }}
    </flux:subheading>
    <flux:separator variant="subtle" />

    <section class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 mt-6 max-w-lg">
        <form wire:submit.prevent="save" class="space-y-5">

            <div>
                <label for="service_category_id"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                <select id="service_category_id" wire:model="service_category_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    <option selected="">-- Pilih Kategori --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                    @error('service_category_id')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </select>
            </div>

            <!-- Nama -->
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                    Layanan</label>
                <input type="text" id="name" wire:model="name"
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 
                       dark:bg-gray-700 dark:border-gray-600 dark:text-white 
                       focus:ring-primary-500 focus:border-primary-500"
                    placeholder="Masukkan nama lengkap">
                @error('name')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Price -->
            <div>
                <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga</label>
                <input type="number" id="price" wire:model="price"
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 
                       dark:bg-gray-700 dark:border-gray-600 dark:text-white 
                       focus:ring-primary-500 focus:border-primary-500"
                    placeholder="Masukkan harga layanan" min="0" step="1000">
                @error('price')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit -->
            <div class="pt-4 flex justify-end gap-2">
                <!-- Tombol Kembali -->
                <a wire:navigate href="{{ route('admin.service') }}"
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
                    Simpan
                </button>
            </div>

        </form>
    </section>

</div>
