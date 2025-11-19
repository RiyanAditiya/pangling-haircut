<nav class="fixed w-full bg-black/80 backdrop-blur-sm text-white shadow z-50">
    <div class="container mx-auto flex items-center justify-between px-3 sm:px-6 py-3 sm:py-4">

        <!-- Logo -->
        <a wire:navigate href="/" class="text-lg sm:text-xl md:text-2xl font-bold tracking-wide">
            Pangling<span class="text-yellow-400">Haircut</span>
        </a>

        <!-- Menu -->
        <div class="flex items-center gap-4 sm:gap-6 text-sm sm:text-base md:text-lg font-medium">
            <a wire:navigate href="{{ url('/') }}"
                class="{{ request()->is('/') ? 'text-yellow-400' : 'text-white hover:text-yellow-400 transition' }}">
                Home
            </a>

            @can('create booking')
                <a wire:navigate href="{{ url('/booking') }}"
                    class="{{ request()->is('booking') ? 'text-yellow-400' : 'text-white hover:text-yellow-400 transition' }}">
                    Booking
                </a>
            @endcan
        </div>

        <!-- Auth -->
        @guest
            <div class="flex items-center gap-2 sm:gap-3 text-sm sm:text-base md:text-lg font-semibold">
                <a href="{{ route('login') }}"
                    class="bg-yellow-400 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-black hover:bg-yellow-500 transition">
                    Login
                </a>
                <a href="{{ route('register') }}"
                    class="bg-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-black hover:bg-gray-200 transition">
                    Register
                </a>
            </div>
        @endguest

        @auth
            <div class="relative" x-data="{ profile: false }">
                <button @click="profile = !profile"
                    class="flex items-center gap-2 sm:gap-3 text-sm sm:text-base md:text-lg font-medium">
                    <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('img/default.jpg') }}"
                        class="w-7 h-7 sm:w-8 sm:h-8 md:w-9 md:h-9 rounded-full border border-gray-300"
                        alt="{{ Auth::user()->name }}">
                    <span>{{ strtok(Auth::user()->name, ' ') }}</span>
                </button>

                <div x-show="profile" x-cloak @click.away="profile = false"
                    class="absolute right-0 mt-2 w-40 sm:w-48 md:w-56 bg-white text-black rounded-xl shadow-lg text-sm sm:text-base md:text-lg overflow-hidden">
                    <a href="{{ route('customer.profile') }}" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
