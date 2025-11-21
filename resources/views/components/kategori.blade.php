<div class="w-full bg-white pt-14">
    <div class="max-w-[1250px] mx-auto px-4">

        <!-- Judul -->
        <h2 class="text-3xl font-bold mb-10 mt-10 text-gray-800 text-left tracking-wide">
            Kategori Pilihan
        </h2>

        <!-- Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-7">

            @foreach($items as $item)
            <div class="group">
                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm 
                            flex flex-col items-center justify-center
                            transition-all duration-300 
                            hover:border-orange-500 hover:shadow-xl hover:shadow-orange-100 hover:-translate-y-1">

                    <!-- Icon -->
                    <div class="w-20 h-20 mb-4 flex items-center justify-center">
                        <img src="{{ $item['icon'] }}" 
                             alt="{{ $item['name'] }}" 
                             class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105">
                    </div>

                    <!-- Title -->
                    <p class="font-semibold text-gray-700 text-lg text-center 
                              group-hover:text-orange-600 transition-colors">
                        {{ $item['name'] }}
                    </p>
                </div>
            </div>
            @endforeach

        </div>

    </div>
</div>
