<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-black text-3xl tracking-tight text-blue-900 drop-shadow-sm flex items-center gap-3">
                <span class="text-3xl">🌍</span> Manajemen Lokasi Bandara
            </h2>
            @if(Auth::user()->role !== 'customer')
            <a href="/locations/create" class="inline-flex items-center justify-center px-8 py-3 text-sm font-bold text-white transition-all duration-300 bg-blue-600 rounded-full shadow-lg hover:shadow-xl hover:shadow-blue-600/30 hover:bg-blue-700 whitespace-nowrap">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Tambah Bandara Baru
            </a>
            @endif
        </div>
    </x-slot>

    <!-- BACKGROUND CLEAN PUTIH & ABU-ABU -->
    <div class="py-8 min-h-screen bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Notifikasi Sukses/Error -->
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-xl shadow-sm flex items-center gap-3">
                <div class="bg-emerald-500 p-1.5 rounded-full text-white shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="text-emerald-800 font-bold text-sm tracking-wide">{{ session('success') }}</p>
            </div>
            @endif

            @if ($errors->any())
            <div class="bg-red-50 border border-red-200 p-4 rounded-xl shadow-sm flex items-center gap-3">
                <div class="bg-red-500 p-1.5 rounded-full text-white shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <p class="text-red-800 font-bold text-sm tracking-wide">{{ $errors->first() }}</p>
            </div>
            @endif

            <!-- FITUR PENCARIAN (Clean Design) -->
            <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100">
                <form action="/locations" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <div class="relative w-full sm:flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari nama bandara atau kode negara dari 5000+ data..." 
                               class="w-full pl-12 pr-4 py-3 bg-gray-50 border-gray-200 text-gray-900 font-bold rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:bg-white transition-colors">
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-black px-8 py-3 rounded-xl shadow-md transition-all">
                            CARI LOKASI
                        </button>
                        
                        @if(request('search'))
                            <a href="/locations" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-black px-6 py-3 rounded-xl flex items-center shadow-sm transition-all border border-gray-300">
                                RESET
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- KONTAINER TABEL BANDARA (Corporate Blue Style) -->
            <div class="bg-white shadow-xl shadow-gray-200/50 rounded-[2rem] border border-gray-100 overflow-hidden flex flex-col">
                    
                <div class="flex items-center p-6 border-b border-gray-100 bg-white">
                    <h3 class="text-xl font-black text-blue-950 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span> 
                        Daftar Lokasi Operasional
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-blue-900 text-white">
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-widest border-r border-blue-800 w-24 text-center">ID DB</th>
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-widest border-r border-blue-800">Nama Lengkap Lokasi Bandara</th>
                                @if(Auth::user()->role === 'admin')
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-widest text-center w-32">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-gray-100">
                            @forelse ($locations as $location)
                            <tr class="hover:bg-blue-50/50 transition-colors duration-200 group">
                                
                                <!-- Kolom ID -->
                                <td class="py-5 px-6 font-black text-gray-500 text-center text-sm group-hover:text-blue-700 transition-colors">
                                    #{{ $location->id }}
                                </td>
                                
                                <!-- Kolom Nama Bandara -->
                                <td class="py-5 px-6 text-sm">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 bg-slate-100 rounded-xl group-hover:bg-blue-100 group-hover:text-blue-700 text-slate-500 transition-all duration-300 shadow-sm border border-transparent group-hover:border-blue-200">
                                            <svg class="w-6 h-6 transform -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-900 text-base group-hover:text-blue-900 transition-colors">{{ $location->name }}</p>
                                            <p class="text-[11px] font-bold text-emerald-600 mt-1 flex items-center gap-1.5 uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Terverifikasi & Aktif
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kolom Aksi Hanya Admin -->
                                @if(Auth::user()->role === 'admin')
                                <td class="py-5 px-6 align-middle text-center">
                                    <form action="/locations/{{ $location->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus lokasi bandara ini? Pastikan lokasi ini belum dipakai di resi pengiriman manapun.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-50 text-red-600 border border-red-100 hover:bg-red-600 hover:text-white px-5 py-2 rounded-xl text-xs font-black shadow-sm transition-all">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-16 text-center bg-gray-50/50">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="bg-white p-6 rounded-full shadow-sm mb-4 border border-gray-100">
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <p class="text-xl font-black text-gray-700">Data bandara tidak ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Link Pagination -->
                <div class="p-6 border-t border-gray-100 bg-white">
                    {{ $locations->appends(request()->query())->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>