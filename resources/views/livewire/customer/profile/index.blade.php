<section class="bg-gray-50 dark:bg-gray-900 pt-16 md:pt-24 pb-10 min-h-screen">
    <div class="mx-auto max-w-4xl px-4 lg:px-8">

        {{-- Alert Sukses --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition:leave.duration.500ms
                class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-800 dark:border-green-700 dark:text-green-300"
                role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl">

            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-6 text-center">Profil Pengguna</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start mb-8">

                {{-- KOLOM KIRI: FOTO PROFIL --}}
                <div class="md:col-span-1 flex flex-col items-center">
                    <p class="text-base font-semibold text-gray-700 dark:text-gray-300 mb-3 md:hidden">Foto Profil</p>

                    <div class="w-40 h-40 overflow-hidden border-1 border-gray-300 shadow-lg">
                        <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('img/default.jpg') }}"
                            alt="Foto Profil" class="w-full h-full object-cover">
                    </div>
                </div>

                {{-- KOLOM KANAN: DATA PROFIL --}}
                <div class="md:col-span-2 space-y-4">
                    {{-- Data Nama --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</label>
                        <p class="mt-1 text-base font-bold text-gray-900 dark:text-white">
                            {{ $name ?? ($user->name ?? 'N/A') }}
                        </p>
                        <div class="border-b border-gray-200 dark:border-gray-700 mt-2"></div>
                    </div>

                    {{-- Data Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Email</label>
                        <p class="mt-1 text-base font-bold text-gray-900 dark:text-white">
                            {{ $email ?? ($user->email ?? 'N/A') }}
                        </p>
                        <div class="border-b border-gray-200 dark:border-gray-700 mt-2"></div>
                    </div>

                    {{-- Data Role --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Peran Akun</label>
                        <p class="mt-1 text-base font-bold text-yellow-600 dark:text-yellow-400">
                            {{ ucfirst($user->roles->first()->name ?? 'Customer') }}
                        </p>
                        <div class="border-b border-gray-200 dark:border-gray-700 mt-2"></div>
                    </div>
                </div>
            </div>


            <div
                class="pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-center sm:justify-end gap-3">

                {{-- Tombol Kembali --}}
                <a wire:navigate href="{{ route('home') }}"
                    class="inline-flex justify-center items-center px-5 py-2.5 text-sm font-medium text-white bg-gray-500 rounded-lg hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>

                {{-- Tombol Edit Profil --}}
                <a wire:navigate href="{{ route('customer.profileEdit') }}"
                    class="inline-flex justify-center items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-900">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit Profil
                </a>
            </div>

        </div>

    </div>
</section>
