<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-black text-3xl tracking-tight text-blue-900 drop-shadow-sm flex items-center gap-3">
                <span class="text-3xl">📦</span> Data Pengiriman Logistik
            </h2>
            @if(Auth::user()->role !== 'customer')
            <a href="/shipments/create" class="inline-flex items-center justify-center px-8 py-3 text-sm font-bold text-white transition-all duration-300 bg-blue-600 rounded-full shadow-lg hover:shadow-xl hover:shadow-blue-600/30 hover:bg-blue-700 whitespace-nowrap">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Create New Shipment
            </a>
            @endif
        </div>
    </x-slot>

    <!-- BACKGROUND CLEAN PUTIH & ABU-ABU -->
    <div class="py-8 min-h-screen bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Notifikasi Sukses -->
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-xl shadow-sm flex items-center gap-3">
                <div class="bg-emerald-500 p-1.5 rounded-full text-white shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="text-emerald-800 font-bold text-sm tracking-wide">{{ session('success') }}</p>
            </div>
            @endif

            <!-- KONTAINER TABEL UTAMA (Corporate Blue Style) -->
            <div class="bg-white shadow-xl shadow-gray-200/50 rounded-[2rem] border border-gray-100 overflow-hidden flex flex-col">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <!-- HEADER TABEL BIRU DONGKER -->
                        <thead>
                            <tr class="bg-blue-900 text-white">
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest border-r border-blue-800">Tracking Number</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest border-r border-blue-800">Route</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest border-r border-blue-800 text-center">Status</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest border-r border-blue-800">ETA</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-widest text-center">Actions</th>
                            </tr>
                        </thead>
                        
                        <!-- ISI TABEL -->
                        <tbody class="divide-y-2 divide-gray-100 bg-white">
                            @forelse($shipments as $shipment)
                            <tr class="hover:bg-blue-50/50 transition-colors duration-200 group">
                                
                                <!-- Kolom Resi -->
                                <td class="px-6 py-5 whitespace-nowrap align-top">
                                    <div class="font-black text-gray-900 text-base group-hover:text-blue-700 transition-colors">
                                        {{ $shipment->tracking_number }}
                                    </div>
                                    <div class="text-xs font-bold text-slate-500 mt-1 bg-slate-100 border border-slate-200 inline-block px-2 py-1 rounded-md">{{ $shipment->cargo_type }}</div>
                                </td>
                                
                                <!-- Kolom Rute -->
                                <td class="px-6 py-5 whitespace-nowrap align-top">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2 font-bold text-gray-800">
                                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                            {{ explode(' (', $shipment->origin->name ?? 'Unknown')[0] }}
                                        </div>
                                        <div class="flex items-center gap-2 text-slate-300 ml-1">
                                            <svg class="w-4 h-4 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                        </div>
                                        <div class="flex items-center gap-2 font-bold text-gray-800">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            {{ explode(' (', $shipment->destination->name ?? 'Unknown')[0] }}
                                        </div>
                                    </div>
                                    
                                    @if(!empty($shipment->exception_note))
                                        <div class="text-[10px] font-black text-red-600 mt-3 uppercase tracking-wider flex items-center gap-1 bg-red-50 p-1.5 rounded-md inline-flex border border-red-200">
                                            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span> Issue Detected
                                        </div>
                                    @endif
                                </td>

                                <!-- Kolom Status -->
                                <td class="px-6 py-5 whitespace-nowrap align-top text-center">
                                    <span class="bg-blue-50 text-blue-700 border border-blue-200 shadow-sm py-1.5 px-4 rounded-full text-xs font-black tracking-wide inline-block">
                                        {{ $shipment->status }}
                                    </span>
                                </td>

                                <!-- Kolom ETA -->
                                <td class="px-6 py-5 whitespace-nowrap align-top">
                                    <div class="text-sm font-black text-emerald-700 bg-emerald-50 px-3 py-2 rounded-xl border border-emerald-200 inline-block shadow-sm group-hover:bg-emerald-100 transition-colors">
                                        {{ \Carbon\Carbon::parse($shipment->expected_arrival)->format('d M Y') }}
                                        <span class="block text-[11px] font-bold text-emerald-600 mt-0.5">
                                            Jam {{ \Carbon\Carbon::parse($shipment->expected_arrival)->format('H:i') }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Kolom Aksi -->
                                <td class="px-6 py-5 whitespace-nowrap align-middle">
                                    <div class="flex justify-center items-center gap-2">
                                        
                                        <!-- Tombol Detail / Lacak -->
                                        <a href="/shipments/{{ $shipment->id }}" class="p-2.5 text-blue-600 bg-blue-50 border border-blue-100 rounded-xl hover:bg-blue-600 hover:text-white hover:shadow-md transition-all duration-300 transform hover:-translate-y-1" title="Track & Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>

                                        @if(Auth::user()->role !== 'customer')
                                        <!-- Tombol Edit -->
                                        <a href="/shipments/{{ $shipment->id }}/edit" class="p-2.5 text-amber-600 bg-amber-50 border border-amber-100 rounded-xl hover:bg-amber-500 hover:text-white hover:shadow-md transition-all duration-300 transform hover:-translate-y-1" title="Edit Shipment">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>

                                        <!-- Tombol Delete -->
                                        <form action="/shipments/{{ $shipment->id }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data resi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2.5 text-red-600 bg-red-50 border border-red-100 rounded-xl hover:bg-red-600 hover:text-white hover:shadow-md transition-all duration-300 transform hover:-translate-y-1" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center bg-gray-50/50">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="bg-white p-6 rounded-full shadow-sm mb-4 border border-gray-200">
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        </div>
                                        <p class="text-xl font-black text-gray-700">Belum ada data pengiriman.</p>
                                        <p class="text-sm mt-2 text-gray-500 font-bold">Klik tombol "Create New Shipment" di atas untuk mulai.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>