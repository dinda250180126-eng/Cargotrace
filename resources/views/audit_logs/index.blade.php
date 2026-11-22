<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-black text-3xl tracking-tight text-blue-900 drop-shadow-sm flex items-center gap-3">
                <span class="text-3xl">🛡️</span> Audit Log <span class="text-lg font-bold text-gray-500 bg-gray-200 px-3 py-1 rounded-full ml-2">(System Activity)</span>
            </h2>
        </div>
    </x-slot>

    <!-- BACKGROUND CLEAN PUTIH & ABU-ABU -->
    <div class="py-8 min-h-screen bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- KONTAINER TABEL UTAMA (Corporate Blue Style) -->
            <div class="bg-white shadow-xl shadow-gray-200/50 rounded-[2rem] border border-gray-100 overflow-hidden flex flex-col">
                
                <!-- Judul Tabel -->
                <div class="flex justify-between items-center p-6 border-b border-gray-100 bg-white">
                    <h3 class="text-xl font-black text-blue-950 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span> 
                        Rekam Jejak Aktivitas Sistem
                    </h3>
                </div>

                <!-- Pembungkus Tabel agar bisa di-scroll di HP -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <!-- HEADER TABEL BIRU DONGKER -->
                        <thead>
                            <tr class="bg-blue-900 text-white">
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-widest border-r border-blue-800 whitespace-nowrap">Waktu (WIB)</th>
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-widest border-r border-blue-800 whitespace-nowrap">Pelaku (Admin)</th>
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-widest border-r border-blue-800 text-center">Aksi</th>
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-widest">Deskripsi Detail</th>
                            </tr>
                        </thead>
                        
                        <!-- ISI DATA -->
                        <tbody class="divide-y-2 divide-gray-100 bg-white">
                            @forelse($logs as $log)
                            <tr class="hover:bg-blue-50/50 transition-colors duration-200 group">
                                
                                <!-- Kolom Waktu -->
                                <td class="py-5 px-6 whitespace-nowrap align-middle">
                                    <div class="text-sm font-bold text-gray-600 group-hover:text-blue-800 transition-colors flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }}
                                    </div>
                                </td>

                                <!-- Kolom Pelaku -->
                                <td class="py-5 px-6 whitespace-nowrap align-middle">
                                    <div class="font-black text-gray-900 text-sm group-hover:text-blue-900 transition-colors flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-black text-[10px] border border-slate-200">
                                            {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                        </div>
                                        {{ $log->user->name ?? 'System' }}
                                    </div>
                                </td>

                                <!-- Kolom Aksi (Badges Dinamis) -->
                                <td class="py-5 px-6 whitespace-nowrap align-middle text-center">
                                    @if(strtoupper($log->action) === 'UPDATE')
                                        <span class="bg-blue-50 text-blue-700 border border-blue-200 py-1.5 px-3 rounded-full text-[10px] font-black tracking-widest uppercase shadow-sm">
                                            UPDATE
                                        </span>
                                    @elseif(strtoupper($log->action) === 'CREATE' || strtoupper($log->action) === 'INSERT')
                                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 py-1.5 px-3 rounded-full text-[10px] font-black tracking-widest uppercase shadow-sm">
                                            CREATE
                                        </span>
                                    @elseif(strtoupper($log->action) === 'DELETE')
                                        <span class="bg-red-50 text-red-700 border border-red-200 py-1.5 px-3 rounded-full text-[10px] font-black tracking-widest uppercase shadow-sm">
                                            DELETE
                                        </span>
                                    @else
                                        <span class="bg-gray-50 text-gray-700 border border-gray-200 py-1.5 px-3 rounded-full text-[10px] font-black tracking-widest uppercase shadow-sm">
                                            {{ $log->action }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Kolom Deskripsi -->
                                <td class="py-5 px-6 align-middle">
                                    <p class="text-sm font-bold text-gray-700 leading-relaxed group-hover:text-blue-900 transition-colors">
                                        {{ $log->description }}
                                    </p>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center bg-gray-50/50">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="bg-white p-6 rounded-full shadow-sm mb-4 border border-gray-100">
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <p class="text-xl font-black text-gray-700">Belum ada rekam jejak aktivitas.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Link Pagination (Opsional jika datanya banyak) -->
                @if(method_exists($logs, 'links'))
                <div class="p-6 border-t border-gray-100 bg-white">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>