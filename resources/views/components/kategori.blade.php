<div class="w-full bg-white pt-14">
    <div class="max-w-[1250px] mx-auto px-4">

        <!-- Judul -->
        <h2 class="text-3xl font-bold mb-10 mt-10 text-gray-800 text-left tracking-wide">
            Kategori Pilihan
        </h2>

        <!-- 2 Bagian -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 items-start">

            <!-- BAGIAN KIRI: MASKOT -->
            <div class="flex justify-center md:justify-start">
                <img src="/images/maskot2.png" 
                     alt="Maskot"
                     class="w-72 md:w-96 object-contain drop-shadow-xl">
            </div>

            <!-- BAGIAN KANAN: GRID KATEGORI -->
            <div class="md:col-span-2">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 gap-7">

                    @foreach($items as $item)
                    <div class="group">
                        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm 
                                    transition-all duration-300 hover:border-orange-500 
                                    hover:shadow-xl hover:shadow-orange-100 hover:-translate-y-1">

                            <!-- Full Image -->
                            <div class="w-full sm:h-full">
                                <img src="{{ $item['icon'] }}"

                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            </div>

                            <!-- Title -->
    
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

        </div>

    </div>
</div>
