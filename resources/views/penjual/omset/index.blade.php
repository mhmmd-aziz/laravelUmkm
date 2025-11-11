<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Omset') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Filter Laporan -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Filter Laporan
                    </h3>
                    {{-- Form ini me-reload halaman dengan parameter GET --}}
                    <form action="{{ route('penjual.omset.index') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <x-input-label for="tanggal_mulai" :value="__('Tanggal Mulai')" />
                                <x-text-input id="tanggal_mulai" type="date" name="tanggal_mulai" class="mt-1 block w-full" :value="$tanggalMulai ?? ''" />
                            </div>
                            <div>
                                <x-input-label for="tanggal_selesai" :value="__('Tanggal Selesai')" />
                                <x-text-input id="tanggal_selesai" type="date" name="tanggal_selesai" class="mt-1 block w-full" :value="$tanggalSelesai ?? ''" />
                            </div>
                            <div class="flex space-x-2">
                                <x-primary-button class="w-full justify-center">
                                    {{ __('Terapkan Filter') }}
                                </x-primary-button>
                                <x-secondary-button-link href="{{ route('penjual.omset.index') }}" class="w-full justify-center">
                                    {{ __('Reset') }}
                                </x-secondary-button-link>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Box Omset Total -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Omset Hari Ini</h4>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($omsetHariIni, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Omset Minggu Ini (7 Hari)</h4>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($omsetMingguIni, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Omset Bulan Ini</h4>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($omsetBulanIni, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Omset Tahun Ini</h4>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($omsetTahunIni, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Grafik Omset -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    {{-- PERBAIKAN: Judul diubah ke 30 Hari --}}
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Grafik Omset (Sesuai Filter)
                    </h3>
                    <div class="h-80"> {{-- Beri tinggi agar canvas terlihat --}}
                        {{-- PERBAIKAN: Pindahkan data JSON dari script ke data attribute untuk menghindari error Vite/Blade --}}
                        <canvas id="omsetChart" data-grafik="{{ $dataGrafikJson }}"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tabel Riwayat Transaksi Selesai -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Riwayat Transaksi Selesai (Sesuai Filter)
                        </h3>
                        {{-- Form untuk Ekspor Excel (melewatkan parameter filter) --}}
                        <form action="{{ route('penjual.omset.export') }}" method="GET">
                            <input type="hidden" name="tanggal_mulai" value="{{ $tanggalMulai ?? '' }}">
                            <input type="hidden" name="tanggal_selesai" value="{{ $tanggalSelesai ?? '' }}">
                            <x-primary-button>
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                Ekspor ke Excel
                            </x-primary-button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tanggal Selesai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Invoice ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Pembeli</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total Omset (Rp)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                {{-- PERBAIKAN: Gunakan $transaksiSelesai --}}
                                @forelse ($transaksiSelesai as $transaksi)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $transaksi->updated_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $transaksi->invoice_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $transaksi->user->name ?? 'Pembeli Dihapus' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                            Tidak ada data omset yang ditemukan untuk rentang tanggal ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $transaksiSelesai->links() }}
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
                const ctx = document.getElementById('omsetChart');
                
                // --- INI DIA PERBAIKANNYA ---
                // Kita ambil data JSON dari data attribute, lalu parse
                const dataGrafikJson = ctx.dataset.grafik;
                const dataGrafik = JSON.parse(dataGrafikJson); 
                // --- BATAS PERBAIKAN ---

                // Cek tema (dark mode)
                const isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                const labelColor = isDarkMode ? '#CBD5E1' : '#4B5563'; // gray-300 / gray-600

                new Chart(ctx, {
                    type: 'line', // Tipe grafik (garis)
                    data: {
                        labels: dataGrafik.labels, // Label sumbu X (Tanggal)
                        datasets: [{
                            label: 'Omset (Rp)',
                            data: dataGrafik.data, // Data sumbu Y (Omset)
                            fill: true,
                            backgroundColor: 'rgba(79, 70, 229, 0.2)', // indigo-600 transparan
                            borderColor: 'rgba(79, 70, 229, 1)', // indigo-600
                            tension: 0.3, // Membuat garis sedikit melengkung
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
                                    // Format Rupiah (sederhana)
                                    callback: function(value, index, values) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                },
                                grid: {
                                    color: gridColor
                                }
                            },
                            x: {
                                ticks: {
                                    color: labelColor
                                },
                                grid: {
                                    display: false // Sembunyikan grid X
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false // Sembunyikan legenda
                            },
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