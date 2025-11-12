<div>
    <!-- Hero -->
    <section id="home"
        class="relative bg-gradient-to-r from-gray-900 to-gray-700 text-white min-h-screen flex items-center justify-center">
        <div class="container mx-auto flex flex-col md:flex-row items-center justify-center px-12 pt-20 gap-10">

            <!-- Text -->
            <div class="md:w-1/2 space-y-6 text-center md:text-left">
                <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                    Premium Haircuts for <span class="text-yellow-400">Gentlemen</span>
                </h1>
                <p class="text-base md:text-lg text-gray-300 max-w-lg mx-auto md:mx-0">
                    Nikmati pengalaman cukur modern dengan barber profesional kami.
                </p>
                <a wire:navigate href="/booking"
                    class="inline-block bg-yellow-400 text-black font-semibold px-6 py-3 rounded-full hover:bg-yellow-500 transition">
                    Book Now
                </a>
            </div>

            <!-- Image -->
            <div class="md:w-1/2 flex justify-center mb-6 md:mb-0">
                <div class="relative">
                    <div class="absolute -inset-2 bg-yellow-400 rounded-full blur-2xl opacity-30 animate-pulse">
                    </div>
                    <img src="{{ asset('img/barber.jpg') }}" alt="Barber Hero"
                        class="relative w-64 h-64 md:w-96 md:h-96 rounded-full shadow-2xl object-cover border-4 border-white" />
                </div>
            </div>


        </div>
    </section>



    <!-- About Owner -->
    <section id="about" class="py-20 bg-gray-50">
        <div class="container mx-auto flex flex-col md:flex-row items-center gap-12 px-12">

            <!-- Foto Owner -->
            <div class="md:w-1/2 flex flex-col items-center">
                <div class="relative">
                    <!-- Glow Background -->
                    <div class="absolute -inset-2 bg-yellow-400 rounded-full blur-2xl opacity-30 animate-pulse">
                    </div>

                    <!-- Foto -->
                    <img src="{{ asset('img/fitron.jpg') }}" alt="Owner"
                        class="relative w-48 h-48 md:w-64 md:h-64 rounded-full shadow-2xl object-cover border-4 border-white" />
                </div>

                <!-- Nama & Posisi -->
                <div class="mt-6 text-center">
                    <h3 class="text-2xl font-bold text-gray-800">Fitron</h3>
                    <p class="text-gray-500">Founder & Master Barber</p>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="md:w-1/2 space-y-6 text-center md:text-left">
                <h2 class="text-3xl font-bold">Founder Pangling Haircut</h2>
                <p class="text-gray-600">
                    Dengan pengalaman lebih dari 10 tahun di dunia barber, pendiri Pangling Haircut berkomitmen untuk
                    menghadirkan pengalaman potong rambut terbaik bagi setiap pelanggan yang ingin tampil rapi, stylish,
                    dan percaya diri.
                </p>
                <p class="text-gray-600">
                    Visi kami sederhana: menjadikan potongan rambut bukan sekadar rutinitas, tetapi bagian dari seni dan
                    gaya hidup modern pria masa kini.
                </p>
            </div>

        </div>
    </section>


    <!-- Services -->

    <section id="services" class="py-20 bg-gray-800 text-white">
        <div class="container mx-auto px-12 text-center">
            <h2 class="text-3xl font-bold mb-12">Our Services</h2>
            <div class="flex flex-wrap justify-center gap-8">
                @foreach ($categories as $category)
                    <div wire:click="showServices({{ $category->id }})"
                        class="bg-gray-700 shadow-lg rounded-xl p-6 hover:shadow-2xl transition w-72 cursor-pointer">
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                            class="rounded-lg mb-4 w-full h-40 object-cover" />
                        <h3 class="text-xl font-semibold mb-2 text-yellow-500">{{ $category->name }}</h3>
                        <p class="text-gray-300">{{ $category->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Modal --}}
        @if ($showModal && $selectedCategory)
            <div class="fixed inset-0 flex items-center justify-center bg-black/60 z-50">
                <div class="bg-white text-gray-900 rounded-xl p-6 w-11/12 md:w-1/2">
                    <h2 class="text-2xl font-bold mb-4 text-center">
                        {{ $selectedCategory->name }}
                    </h2>
                    <p class="text-gray-600 mb-4 text-center">{{ $selectedCategory->description }}</p>

                    <ul class="divide-y divide-gray-200">
                        @forelse ($selectedCategory->services as $service)
                            <li class="py-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium">{{ $service->name }}</span>
                                    <span class="text-yellow-500 font-semibold">Rp.
                                        {{ number_format($service->price, 0, ',', '.') }}</span>
                                </div>
                            </li>
                        @empty
                            <li class="py-3 text-center text-gray-500">Belum ada layanan tersedia.</li>
                        @endforelse
                    </ul>

                    <div class="text-center mt-6">
                        <button wire:click="closeModal"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </section>




    <!-- Team -->
    <section id="team" class="py-20 bg-gray-50">
        <div class="container mx-auto px-12 text-center">
            <p class="text-yellow-500 uppercase font-semibold">Our Barber Team</p>
            <h2 class="text-3xl font-bold mb-12">Meet Our Hair Cut Expert Barber</h2>
            <div class="grid gap-8 sm:grid-cols-2 md:grid-cols-5">
                @foreach ($barbers as $barber)
                    <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-2xl transition">
                        <img src="{{ $barber->image ? asset('storage/' . $barber->image) : asset('img/default.jpg') }}"
                            alt="Barber" class="w-28 h-28 mx-auto rounded-full object-cover" />
                        <h3 class="mt-4 text-lg font-semibold">{{ $barber->name }}</h3>
                        <p class="text-sm text-gray-500">Master Barber</p>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    <!-- Schedule -->
    <section id="schedule" class="py-20 bg-gradient-to-b from-gray-100 to-white">
        <div class="container mx-auto px-6 md:px-12 text-center">
            <p class="text-yellow-500 uppercase font-semibold tracking-widest mb-2">Our Schedule</p>
            <h2 class="text-3xl font-bold mb-12 text-gray-800">Barber Schedule</h2>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($schedules as $day => $items)
                    @php
                        // Konversi nama hari ke bahasa Indonesia jika masih pakai bahasa Inggris
                        $hariIndo =
                            [
                                'monday' => 'Senin',
                                'tuesday' => 'Selasa',
                                'wednesday' => 'Rabu',
                                'thursday' => 'Kamis',
                                'friday' => 'Jumat',
                                'saturday' => 'Sabtu',
                                'sunday' => 'Minggu',
                            ][$day] ?? $day;

                        // Pisahkan barber libur dan yang bekerja
                        $offBarbers = $items->filter(fn($i) => $i->is_off ?? !$i->barbershop_id);
                        $working = $items
                            ->filter(fn($i) => !($i->is_off ?? !$i->barbershop_id))
                            ->groupBy('barbershop.name');
                    @endphp

                    <div
                        class="bg-white shadow-md rounded-xl p-5 hover:shadow-lg transition text-left border border-gray-100">
                        <h3 class="text-lg font-bold mb-3 text-center text-gray-800">
                            {{ $hariIndo }}
                        </h3>

                        {{-- Jadwal kerja --}}
                        @forelse ($working as $barbershopName => $list)
                            <p class="text-sm mb-2">
                                <span class="font-semibold text-yellow-600">{{ $barbershopName }}:</span>
                                <span class="text-gray-700">{{ $list->pluck('barber.name')->join(', ') }}</span>
                            </p>
                        @empty
                            <p class="text-gray-500 italic text-sm">Tidak ada jadwal hari ini</p>
                        @endforelse

                        {{-- Barber yang libur --}}
                        @if ($offBarbers->isNotEmpty())
                            <p class="text-sm mt-3 border-t pt-2 border-gray-200">
                                <span class="font-semibold text-red-500">Libur:</span>
                                <span class="text-gray-700">{{ $offBarbers->pluck('barber.name')->join(', ') }}</span>
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>

        </div>
    </section>


</div>
