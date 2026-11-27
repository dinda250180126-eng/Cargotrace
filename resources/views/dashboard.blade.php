<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-3xl tracking-tight text-blue-900 drop-shadow-sm">
                {{ __('Overview Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <!-- Memanggil Library Chart.js untuk Grafik -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- BACKGROUND CLEAN PUTIH & ABU-ABU -->
    <div class="py-8 min-h-screen bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- 1. HERO BANNER (Gambar Pesawat Terbang) -->
            <div class="relative w-full h-80 sm:h-96 rounded-[2rem] overflow-hidden shadow-2xl shadow-blue-900/20 group">
                <!-- Gambar Pesawat -->
                <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2074&auto=format&fit=crop" alt="Airplane Flight" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                
                <!-- Overlay Gradien Biru Tua (Agar teks terbaca) -->
                <div class="absolute inset-0 bg-gradient-to-r from-blue-900/95 via-blue-900/70 to-transparent"></div>
                
                <!-- Konten Banner yang Sudah Diperbarui -->
                <div class="absolute inset-0 p-8 sm:p-12 flex flex-col justify-center w-full md:w-2/3 lg:w-1/2">
                    
                    <!-- Judul Utama -->
                    <h3 class="text-4xl sm:text-5xl font-black text-white mb-8 leading-tight">Delivery of Goods Well</h3>
                    
                    @if(Auth::user()->role !== 'customer')
                    <div>
                        <a href="/shipments/create" class="inline-flex items-center justify-center px-8 py-3.5 text-sm font-black text-blue-900 transition-all duration-300 bg-white rounded-full shadow-lg hover:shadow-xl hover:scale-105 hover:bg-blue-50">
                            <svg class="w-5 h-5 mr-2 -ml-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                            Buat Resi Baru
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- 2. Kartu Statistik (KPIs) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Total Pengiriman -->
                <div class="bg-white shadow-xl shadow-gray-200/50 rounded-[2rem] border border-gray-100 p-6 flex flex-col justify-between group hover:-translate-y-2 transition-transform duration-300">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="p-3 bg-blue-50 text-blue-700 rounded-2xl group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-black text-gray-500 uppercase tracking-widest">Total Pengiriman</p>
                            <p class="text-4xl font-black text-blue-950 mt-1">{{ \App\Models\Shipment::count() }}</p>
                        </div>
                    </div>
                    <!-- Rincian Status Mini -->
                    <div class="flex gap-2 flex-wrap border-t border-gray-100 pt-4">
                        @foreach($statusBreakdown as $status => $count)
                            <span class="text-[10px] font-bold bg-blue-50 text-blue-800 px-3 py-1.5 rounded-full uppercase tracking-wider border border-blue-100">
                                {{ $count }} {{ $status }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Total Bandara -->
                <div class="bg-white shadow-xl shadow-gray-200/50 rounded-[2rem] border border-gray-100 p-6 flex items-center space-x-4 group hover:-translate-y-2 transition-transform duration-300">
                    <div class="p-3 bg-sky-50 text-sky-600 rounded-2xl group-hover:scale-110 group-hover:bg-sky-600 group-hover:text-white transition-all shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-500 uppercase tracking-widest">Jaringan Bandara</p>
                        <p class="text-4xl font-black text-blue-950 mt-1">{{ \App\Models\Location::count() }}</p>
                    </div>
                </div>

                <!-- Total Pengguna -->
                <div class="bg-white shadow-xl shadow-gray-200/50 rounded-[2rem] border border-gray-100 p-6 flex items-center space-x-4 group hover:-translate-y-2 transition-transform duration-300">
                    <div class="p-3 bg-slate-100 text-slate-700 rounded-2xl group-hover:scale-110 group-hover:bg-slate-700 group-hover:text-white transition-all shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-500 uppercase tracking-widest">Pengguna Sistem</p>
                        <p class="text-4xl font-black text-blue-950 mt-1">{{ \App\Models\User::count() }}</p>
                    </div>
                </div>
            </div>

            <!-- 3. Grafik & Akses Cepat -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Grafik Chart.js -->
                <div class="lg:col-span-2 bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 p-8 flex flex-col">
                    <h3 class="text-lg font-black text-blue-950 mb-6 border-b border-gray-100 pb-3">Distribusi Status Pengiriman</h3>
                    <div class="relative flex-grow min-h-[250px] w-full">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <!-- Akses Cepat (Quick Actions) dengan Efek Timbul 3D -->
                <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 p-8 flex flex-col">
                    <h3 class="text-lg font-black text-blue-950 mb-6 border-b border-gray-100 pb-3">Akses Cepat</h3>
                    <div class="grid grid-cols-2 gap-5 flex-grow">
                        
                        <!-- Tombol 1 -->
                        <a href="/shipments" class="block p-5 rounded-[1.5rem] bg-white border border-gray-200 border-b-[5px] border-b-gray-300 shadow-sm hover:shadow-xl hover:shadow-blue-900/10 hover:-translate-y-1 hover:border-b-blue-600 hover:bg-blue-50/50 transition-all duration-300 text-center group flex flex-col justify-center items-center">
                            <span class="block text-4xl mb-3 group-hover:scale-110 group-hover:-rotate-3 transition-transform drop-shadow-sm">📦</span>
                            <span class="font-black text-gray-700 group-hover:text-blue-800 text-[10px] uppercase tracking-wider">Pengiriman</span>
                        </a>
                        
                        <!-- Tombol 2 -->
                        <a href="/locations" class="block p-5 rounded-[1.5rem] bg-white border border-gray-200 border-b-[5px] border-b-gray-300 shadow-sm hover:shadow-xl hover:shadow-blue-900/10 hover:-translate-y-1 hover:border-b-blue-600 hover:bg-blue-50/50 transition-all duration-300 text-center group flex flex-col justify-center items-center">
                            <span class="block text-4xl mb-3 group-hover:scale-110 group-hover:rotate-3 transition-transform drop-shadow-sm">✈️</span>
                            <span class="font-black text-gray-700 group-hover:text-blue-800 text-[10px] uppercase tracking-wider">Bandara</span>
                        </a>
                        
                        @if(Auth::user()->role === 'admin')
                        <!-- Tombol 3 -->
                        <a href="/users" class="block p-5 rounded-[1.5rem] bg-white border border-gray-200 border-b-[5px] border-b-gray-300 shadow-sm hover:shadow-xl hover:shadow-blue-900/10 hover:-translate-y-1 hover:border-b-blue-600 hover:bg-blue-50/50 transition-all duration-300 text-center group flex flex-col justify-center items-center">
                            <span class="block text-4xl mb-3 group-hover:scale-110 group-hover:-rotate-3 transition-transform drop-shadow-sm">👥</span>
                            <span class="font-black text-gray-700 group-hover:text-blue-800 text-[10px] uppercase tracking-wider">User</span>
                        </a>
                        
                        <!-- Tombol 4 -->
                        <a href="/audit-logs" class="block p-5 rounded-[1.5rem] bg-white border border-gray-200 border-b-[5px] border-b-gray-300 shadow-sm hover:shadow-xl hover:shadow-blue-900/10 hover:-translate-y-1 hover:border-b-blue-600 hover:bg-blue-50/50 transition-all duration-300 text-center group flex flex-col justify-center items-center">
                            <span class="block text-4xl mb-3 group-hover:scale-110 group-hover:rotate-3 transition-transform drop-shadow-sm">🛡️</span>
                            <span class="font-black text-gray-700 group-hover:text-blue-800 text-[10px] uppercase tracking-wider">Audit Log</span>
                        </a>
                        @endif

                    </div>
                </div>
            </div>

            <!-- 4. Tabel Pengiriman Terbaru -->
            <div class="bg-white shadow-xl shadow-gray-200/50 rounded-[2rem] border border-gray-100 overflow-hidden">
                <div class="flex justify-between items-center p-6 border-b border-gray-100">
                    <h3 class="text-lg font-black text-blue-950">5 Pengiriman Terbaru</h3>
                    <a href="/shipments" class="text-blue-700 hover:text-white text-sm font-bold bg-blue-50 hover:bg-blue-700 border border-blue-100 hover:border-blue-700 px-5 py-2 rounded-full transition-all">Lihat Semua &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-blue-900 text-white">
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-widest border-r border-blue-800">No. Resi</th>
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-widest border-r border-blue-800">Rute</th>
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-widest border-r border-blue-800">Status</th>
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-gray-100">
                            @forelse ($recentShipments as $shipment)
                            <tr class="hover:bg-blue-50/50 transition-colors group">
                                <td class="py-4 px-6 font-black text-gray-900 text-sm group-hover:text-blue-700 transition-colors">
                                    {{ $shipment->tracking_number }}
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-700 font-bold">
                                    {{ explode(' (', $shipment->origin->name ?? '-')[0] }} 
                                    <span class="text-blue-400 font-black mx-1">&rarr;</span> 
                                    {{ explode(' (', $shipment->destination->name ?? '-')[0] }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="bg-blue-100 text-blue-800 border border-blue-200 py-1.5 px-3 rounded-full text-xs font-black shadow-sm">
                                        {{ $shipment->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <a href="/shipments/{{ $shipment->id }}" class="text-blue-700 hover:text-white hover:bg-blue-700 font-black text-xs uppercase tracking-wider bg-white border border-blue-200 px-4 py-2 rounded-xl transition-all shadow-sm">Lacak</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center text-gray-400 font-bold italic">Belum ada data pengiriman.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Script Inisialisasi Grafik Chart.js (Biru Tua) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('statusChart').getContext('2d');
            const labels = {!! json_encode($statusBreakdown->keys()) !!};
            const data = {!! json_encode($statusBreakdown->values()) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Resi',
                        data: data,
                        backgroundColor: 'rgba(37, 99, 235, 0.85)', // Warna Biru
                        borderColor: 'rgba(30, 58, 138, 1)', // Warna Biru Tua
                        borderWidth: 2,
                        borderRadius: 6,
                        hoverBackgroundColor: 'rgba(29, 78, 216, 1)' 
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, font: { weight: 'bold', color: '#64748b' } },
                            grid: { color: 'rgba(0,0,0,0.04)' }
                        },
                        x: {
                            ticks: { font: { weight: 'bold', color: '#334155' } },
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(30, 58, 138, 0.95)',
                            titleFont: { size: 14, weight: 'bold' },
                            padding: 12,
                            cornerRadius: 8
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>