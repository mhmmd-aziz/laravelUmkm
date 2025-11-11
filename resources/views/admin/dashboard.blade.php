<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Salam Pembuka -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-semibold">{{ __('Selamat Datang') }}, {{ Auth::user()->name }}!</h3>
                    <p class="mt-1 text-gray-600 dark:text-gray-400">
                        {{ __('Anda berada di panel admin. Di sini Anda bisa mengelola pengguna, melihat insight total penjualan, dan memanajemen kategori produk.') }}
                    </p>
                </div>
            </div>

            <!-- K-Stat (Kartu Statistik) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Total Omset -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg rounded-lg p-6">
                    <h4 class="text-sm font-medium uppercase tracking-wider">{{ __('Total Omset') }}</h4>
                    <p class="mt-2 text-4xl font-bold">Rp {{ number_format($totalOmset, 0, ',', '.') }}</p>
                    <p class="mt-1 text-sm opacity-90">{{ __('(Hanya dari transaksi \'selesai\')') }}</p>
                </div>
                <!-- Total Transaksi -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Total Transaksi') }}</h4>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalTransaksi, 0, ',', '.') }}</p>
                </div>
                <!-- Total Produk -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Total Produk') }}</h4>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalProduk, 0, ',', '.') }}</p>
                </div>
                <!-- Total Users -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Total Pengguna') }}</h4>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalUsers, 0, ',', '.') }}</p>
                </div>
                <!-- Total Pembeli -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Total Pembeli') }}</h4>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalPembeli, 0, ',', '.') }}</p>
                </div>
                <!-- Total Penjual -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Total Penjual') }}</h4>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalPenjual, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Grafik Omset -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Grafik Omset Platform (30 Hari Terakhir)') }}
                    </h3>
                    <div class="h-80">
                        <canvas id="adminOmsetChart" data-grafik="{{ $dataGrafikJson }}"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Import Chart.js (hanya di halaman ini) --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('adminOmsetChart');
                
                const dataGrafikJson = ctx.dataset.grafik;
                const dataGrafik = JSON.parse(dataGrafikJson); 

                const isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                const labelColor = isDarkMode ? '#CBD5E1' : '#4B5563';

                new Chart(ctx, {
                    type: 'line', 
                    data: {
                        labels: dataGrafik.labels, // Label sumbu X (Tanggal)
                        datasets: [{
                            label: '{{ __("Omset (Rp)") }}',
                            data: dataGrafik.data, // Data sumbu Y (Omset)
                            fill: true,
                            backgroundColor: 'rgba(79, 70, 229, 0.2)', // indigo-600 transparan
                            borderColor: 'rgba(79, 70, 229, 1)', // indigo-600
                            tension: 0.3, 
                            pointBackgroundColor: 'rgba(79, 70, 229, 1)',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: labelColor,
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                },
                                grid: { color: gridColor }
                            },
                            x: {
                                ticks: { color: labelColor },
                                grid: { display: false }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>