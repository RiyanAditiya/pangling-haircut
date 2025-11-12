<div class="flex flex-col gap-6">
    <div class="text-center mb-3"> {{-- pengatur jarak antar judul dan paragraf --}}
        <h1 class="text-4xl font-extrabold tracking-tight drop-shadow-sm leading-tight">
            <span class="text-yellow-600 dark:text-yellow-400">Pangling</span>
            <span class="text-gray-800 dark:text-gray-100">Haircut</span>
        </h1>

        <p class="text-md text-gray-600 dark:text-gray-400 mt-0.5">
            {{ __('Create an account!') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center text-yellow-600 dark:text-yellow-400 font-medium" :status="session('status')" />

    <form method="POST" wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name"
            :placeholder="__('Full name')" class="focus:ring-yellow-500 dark:focus:ring-yellow-400" />

        <!-- Email Address -->
        <flux:input wire:model="email" :label="__('Email address')" type="email" required autocomplete="email"
            placeholder="email@example.com" class="focus:ring-yellow-500 dark:focus:ring-yellow-400" />

        <!-- Password -->
        <flux:input wire:model="password" :label="__('Password')" type="password" required autocomplete="new-password"
            :placeholder="__('Password')" viewable class="focus:ring-yellow-500 dark:focus:ring-yellow-400" />

        <!-- Confirm Password -->
        <flux:input wire:model="password_confirmation" :label="__('Confirm password')" type="password" required
            autocomplete="new-password" :placeholder="__('Confirm password')" viewable
            class="focus:ring-yellow-500 dark:focus:ring-yellow-400" />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary"
                class="w-full bg-yellow-600 hover:bg-yellow-700 dark:bg-yellow-500 dark:hover:bg-yellow-400 text-white font-semibold transition duration-150 ease-in-out">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-gray-600 dark:text-gray-400">
        <span>{{ __('Already have an account?') }}</span>
        <flux:link :href="route('login')" wire:navigate
            class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-300 font-medium transition-colors duration-150">
            {{ __('Log in') }}
        </flux:link>
    </div>
</div>
