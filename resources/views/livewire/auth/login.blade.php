<div class="flex flex-col gap-6">

    <div class="text-center mb-3"> {{-- pengatur jarak antar judul dan paragraf --}}
        <h1 class="text-4xl font-extrabold tracking-tight drop-shadow-sm leading-tight">
            <span class="text-yellow-600 dark:text-yellow-400">Pangling</span>
            <span class="text-gray-800 dark:text-gray-100">Haircut</span>
        </h1>

        <p class="text-md text-gray-600 dark:text-gray-400 mt-0.5">
            {{ __('Log in to your account') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center text-yellow-600 dark:text-yellow-400 font-medium" :status="session('status')" />

    <form method="POST" wire:submit="login" class="flex flex-col gap-6">
        <!-- Email -->
        <flux:input wire:model="email" :label="__('Email address')" type="email" required autofocus
            autocomplete="email" placeholder="email@example.com"
            class="focus:ring-yellow-500 dark:focus:ring-yellow-400" />

        <!-- Password -->
        <div class="relative">
            <flux:input wire:model="password" :label="__('Password')" type="password" required
                autocomplete="current-password" :placeholder="__('Password')" viewable
                class="focus:ring-yellow-500 dark:focus:ring-yellow-400" />

            @if (Route::has('password.request'))
                <flux:link
                    class="absolute end-0 top-0 text-sm text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-300 transition duration-150"
                    :href="route('password.request')" wire:navigate>
                    {{ __('Forgot your password?') }}
                </flux:link>
            @endif
        </div>

        <!-- Remember Me -->
        <flux:checkbox wire:model="remember" :label="__('Remember me')" class="text-yellow-600 dark:text-yellow-500" />

        <!-- Submit Button -->
        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit"
                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white dark:bg-yellow-500 dark:hover:bg-yellow-400 font-semibold tracking-wide shadow-lg shadow-yellow-500/30 transition duration-150">
                {{ __('Log in') }}
            </flux:button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-gray-600 dark:text-gray-400">
            <span>{{ __('Don\'t have an account?') }}</span>
            <flux:link :href="route('register')" wire:navigate
                class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-300 font-medium transition-colors duration-150">
                {{ __('Sign up') }}
            </flux:link>
        </div>
    @endif
</div>
