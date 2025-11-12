<div>
    <flux:heading size="xl" level="1">{{ __('Detail User') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">
        {{ __('Informasi lengkap user Pangling Haircut') }}
    </flux:subheading>
    <flux:separator variant="subtle" />

    <section class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 mt-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Foto Profil -->
            <div class="flex flex-col items-center">
                @if ($user->image)
                    <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}"
                        class="w-32 h-32 rounded object-cover shadow mb-3">
                @else
                    <img src="{{ asset('img/default.jpg') }}" alt="{{ $user->name }}"
                        class="w-32 h-32 rounded object-cover shadow mb-3">
                @endif

                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($user->getRoleNames()->first()) }}</p>

            </div>

            <!-- Informasi -->
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-500">Nama</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Email</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Role</p>
                    <p class="font-medium text-gray-900 dark:text-white capitalize">
                        {{ ucfirst($user->getRoleNames()->first()) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Dibuat pada</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->created_at->format('d M Y H:i') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Terakhir diperbarui</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->updated_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-6 flex justify-end gap-3">
            <a wire:navigate href="{{ route('admin.users') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-600 rounded-lg hover:bg-gray-700 focus:ring-4 focus:ring-gray-300">
                Kembali
            </a>
            <a wire:navigate href="{{ route('admin.editUser', $user->id) }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-200">
                Edit
            </a>
        </div>
    </section>
</div>
