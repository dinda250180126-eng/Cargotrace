<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-black text-3xl tracking-tight text-blue-900 drop-shadow-sm flex items-center gap-3">
                <span class="text-3xl">👥</span> User Management 
                <span class="text-lg font-bold text-gray-500 bg-gray-200 px-3 py-1 rounded-full ml-2">(Administrator)</span>
            </h2>
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

            <!-- KONTAINER TABEL UTAMA -->
            <div class="bg-white shadow-xl shadow-gray-200/50 rounded-[2rem] border border-gray-100 overflow-hidden flex flex-col">
                
                <div class="flex items-center p-6 border-b border-gray-100 bg-white">
                    <h3 class="text-xl font-black text-blue-950 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span> 
                        Daftar Pengguna Sistem
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <!-- HEADER TABEL BIRU DONGKER -->
                        <thead>
                            <tr class="bg-blue-900 text-white">
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-widest border-r border-blue-800">Name</th>
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-widest border-r border-blue-800">Email Address</th>
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-widest border-r border-blue-800 text-center">Current Role</th>
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-widest text-center">Actions</th>
                            </tr>
                        </thead>
                        
                        <tbody class="divide-y-2 divide-gray-100 bg-white">
                            @forelse($users as $user)
                            <tr class="hover:bg-blue-50/50 transition-colors duration-200 group">
                                
                                <!-- Kolom Nama & Avatar -->
                                <td class="py-5 px-6 whitespace-nowrap align-middle">
                                    <div class="flex items-center gap-4">
                                        <!-- Avatar Inisial -->
                                        <div class="w-11 h-11 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-black text-lg border border-blue-200 shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-900 text-base group-hover:text-blue-800 transition-colors">{{ $user->name }}</p>
                                            <p class="text-xs font-bold text-gray-400 mt-0.5">ID: #{{ $user->id }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kolom Email -->
                                <td class="py-5 px-6 whitespace-nowrap align-middle">
                                    <p class="font-bold text-gray-700 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $user->email }}
                                    </p>
                                </td>

                                <!-- Kolom Role (Badge) -->
                                <td class="py-5 px-6 whitespace-nowrap align-middle text-center">
                                    @if(strtolower($user->role) === 'admin')
                                        <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 border border-blue-200 py-1.5 px-4 rounded-full text-xs font-black shadow-sm uppercase tracking-wide">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-600 border border-slate-200 py-1.5 px-4 rounded-full text-xs font-black shadow-sm uppercase tracking-wide">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ $user->role ?? 'Customer' }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Kolom Aksi -->
                                <td class="py-5 px-6 whitespace-nowrap align-middle text-center">
                                    <!-- Jika user adalah Admin atau dirinya sendiri, jangan tampilkan tombol hapus -->
                                    @if(Auth::id() === $user->id || strtolower($user->role) === 'admin')
                                        <span class="text-xs font-bold text-gray-400 italic bg-gray-50 px-4 py-2 rounded-xl border border-gray-100">
                                            Active Account
                                        </span>
                                    @else
                                        <form action="/users/{{ $user->id }}" method="POST" class="inline-block" onsubmit="return confirm('Peringatan: Menghapus user ini bersifat permanen. Lanjutkan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-50 text-red-600 border border-red-100 hover:bg-red-600 hover:text-white px-5 py-2 rounded-xl text-xs font-black shadow-sm transition-all flex items-center gap-2 mx-auto">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Delete User
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center bg-gray-50/50">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="bg-white p-6 rounded-full shadow-sm mb-4 border border-gray-100">
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </div>
                                        <p class="text-xl font-black text-gray-700">Belum ada user yang terdaftar.</p>
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