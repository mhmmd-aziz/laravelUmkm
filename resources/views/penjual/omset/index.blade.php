<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Omset') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 1. Form Filter & Ekspor -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="GET" action="{{ route('penjual.omset.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <x-input-label for="tanggal_mulai" :value="__('Tanggal Mulai')" />
                            <x-text-input id="tanggal_mulai" type="date" name="tanggal_mulai" class="block mt-1 w-full" :value="request('tanggal_mulai')" />
                        </div>
                        <div>
                            <x-input-label for="tanggal_selesai" :value="__('Tanggal Selesai')" />
                            <x-text-input id="tanggal_selesai" type="date" name="tanggal_selesai" class="block mt-1 w-full" :value="request('tanggal_selesai')" />
                        </div>
                        <div class="flex space-x-2">
                            <x-primary-button>
                                {{ __('Filter') }}
                            </x-primary-button>
                            <x-secondary-button-link href="{{ route('penjual.omset.index') }}">
                                {{ __('Reset') }}
                            </x-secondary-button-link>
                        </div>
                    </form>
                    
                    <!-- Tombol Ekspor -->
                    <div class="mt-4">
                        <x-primary-button-link href="{{ route('penjual.omset.export', request()->query()) }}" target="_blank" class="bg-green-600 dark:bg-green-500 hover:bg-green-700 dark:hover:bg-green-400 focus:bg-green-700 dark:focus:bg-green-400 active:bg-green-800 dark:active:bg-green-300 focus:ring-green-500">
                             {{-- Icon Excel --}}
                            <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            {{ __('Ekspor ke Excel') }}
                        </x-primary-button-link>
                    </div>
                </div>
            </div>

            <!-- 2. Box Total (Harian, Mingguan, Bulanan, Tahunan) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Harian -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Omset Hari Ini</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                        Rp {{ number_format($omsetHariIni, 0, ',', '.') }}
                    </p>
                </div>
                <!-- Mingguan -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Omset 7 Hari Terakhir</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                        Rp {{ number_format($omsetMingguIni, 0, ',', '.') }}
                    </p>
                </div>
                <!-- Bulanan -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Omset Bulan Ini</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                        Rp {{ number_format($omsetBulanIni, 0, ',', '.') }}
                    </p>
                </div>
                <!-- Tahunan -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Omset Tahun Ini</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                        Rp {{ number_format($omsetTahunIni, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- 3. Grafik (Sesuai permintaan Anda) -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Grafik Omset 12 Bulan Terakhir
                    </h3>
                    <div class="h-96">
                        <canvas id="grafikOmset"></canvas>
                    </div>
                </div>
            </div>

            <!-- 4. Tabel Transaksi Selesai (Hasil Filter) -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Riwayat Transaksi Selesai (Hasil Filter)
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tgl Selesai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Invoice ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pembeli</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($transaksisHasilFilter as $transaksi)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                            {{ $transaksi->updated_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $transaksi->invoice_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                            {{ $transaksi->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                            Tidak ada data omset untuk filter ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $transaksisHasilFilter->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Panggil Chart.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('grafikOmset').getContext('2d');
            
            // Ambil data dari PHP (dilewatkan oleh Controller)
            const dataGrafik = @json($dataGrafik);
            
            new Chart(ctx, {
                type: 'bar', // Tipe grafik bar (batang)
                data: {
                    labels: dataGrafik.labels, // Label bulan (Jan, Feb, ...)
                    datasets: [{
                        label: 'Omset (Rp)',
                        data: dataGrafik.data, // Data omset per bulan
                        backgroundColor: 'rgba(79, 70, 229, 0.6)', // Warna Indigo
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // Format angka sebagai Rupiah
                                callback: function(value, index, values) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    plugins: {
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
</x-app-layout>
