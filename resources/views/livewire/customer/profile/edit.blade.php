<div>
    <section class="bg-gray-50 dark:bg-gray-900 pt-16 md:pt-24 pb-10 min-h-screen">
        <div class="mx-auto max-w-2xl px-4 lg:px-8">

            <form wire:submit.prevent="update" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">

                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Edit Profile</h1>



                <!-- Nama -->
                <div class="mb-4">
                    <label for="name"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                    <input type="text" id="name" wire:model="name"
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 
                          dark:bg-gray-700 dark:border-gray-600 dark:text-white 
                          focus:ring-primary-500 focus:border-primary-500">
                    @error('name')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="email" id="email" wire:model="email" autocomplete="email"
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 
                          dark:bg-gray-700 dark:border-gray-600 dark:text-white 
                          focus:ring-primary-500 focus:border-primary-500">
                    @error('email')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <input type="password" id="password" wire:model="password" autocomplete="new-password"
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 
                          dark:bg-gray-700 dark:border-gray-600 dark:text-white 
                          focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Kosongkan password jika tidak diganti">
                    @error('password')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>


                <!-- Upload image -->
                <div class="mt-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white flex items-center gap-1">
                        Foto Profil
                        <span class="text-yellow-600 text-xs cursor-help" title="Boleh dikosongkan">(opsional)</span>
                    </label>

                    <!-- Dropzone -->
                    <div class="w-full flex flex-col items-center justify-center rounded-lg border-2 border-dashed p-6 cursor-pointer transition
                        border-gray-300 bg-gray-50 dark:bg-gray-800 dark:border-gray-600"
                        x-on:click="$refs.image.click()" x-on:dragover.prevent
                        x-on:drop.prevent="
                    const file = $event.dataTransfer.files[0];
                    if(file){
                        $refs.image.files = $event.dataTransfer.files;
                        $refs.image.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                 ">

                        <input type="file" x-ref="image" wire:model="image" accept="image/*" class="hidden">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400 mb-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4M21 16v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4m4-4h10m-6-4h2" />
                        </svg>

                        <p class="text-sm text-gray-600 dark:text-gray-300 text-center">
                            Tarik & letakkan gambar atau <span class="text-indigo-600 font-medium">klik untuk
                                upload</span>
                        </p>

                        <p class="mt-1 text-xs text-gray-400 text-center">
                            Format: JPG, JPEG, PNG | Maks. ukuran: 2 MB
                        </p>
                    </div>

                    <!-- Loading Avatar -->
                    <div wire:loading wire:target="image"
                        class="w-20 h-20 border border-gray-200 rounded-lg bg-gray-50 flex items-center justify-center mt-2">
                        <span class="flex items-center justify-center h-full w-full text-xs font-medium text-blue-800">
                            <span class="px-2 py-1 bg-blue-200 rounded-full animate-pulse"> Loading... </span> </span>
                    </div>

                    <!-- Preview -->
                    @if ($image)
                        <div class="mt-3">
                            <p class="text-sm font-medium mb-1">Preview Baru</p>
                            <img src="{{ $image->temporaryUrl() }}" class="rounded w-20 h-20 object-cover">
                        </div>
                    @elseif($oldImage)
                        <div class="mt-3">
                            <p class="text-sm font-medium mb-1">Foto Saat Ini</p>
                            <img src="{{ asset('storage/' . $oldImage) }}" class="rounded w-20 h-20 object-cover">
                        </div>
                    @endif

                    @error('image')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="pt-4 flex justify-end gap-2">
                    <!-- Tombol Kembali -->
                    <a wire:navigate href="{{ route('home') }}"
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
                        Update Profile
                    </button>

                </div>
            </form>
        </div>
    </section>

</div>
