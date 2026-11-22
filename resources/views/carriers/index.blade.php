<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Ekspedisi Kargo (Carriers)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Notifikasi Sukses/Error -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form Tambah Carrier Baru (Hanya muncul untuk Admin) -->
            @if(Auth::user()->role === 'admin')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('carriers.store') }}" method="POST" class="flex gap-4 items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-gray-700 font-bold mb-2 text-sm">Nama Ekspedisi Baru</label>
                        <input type="text" name="name" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Contoh: Garuda Indonesia Cargo">
                    </div>
                    <div class="w-32">
                        <label class="block text-gray-700 font-bold mb-2 text-sm">Kode</label>
                        <input type="text" name="code" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="GIA">
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 h-[42px] rounded shadow hover:bg-indigo-500 transition font-bold">
                        + Tambah
                    </button>
                </form>
            </div>
            @endif

            <!-- Tabel Daftar Carrier -->
            <div class="bg-white p-6 rounded-lg shadow-sm sm:rounded-lg border border-gray-200">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-blue-100 text-blue-800 border-b-2 border-blue-200">
                            <th class="py-3 px-4 w-16 text-center">ID</th>
                            <th class="py-3 px-4">Nama Ekspedisi</th>
                            <th class="py-3 px-4 text-center">Kode</th>
                            
                            <!-- Kolom Aksi Hanya untuk Admin -->
                            @if(Auth::user()->role === 'admin')
                            <th class="py-3 px-4 w-32 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($carriers as $carrier)
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 text-center text-gray-500">{{ $carrier->id }}</td>
                            <td class="py-3 px-4 font-medium text-gray-700">{{ $carrier->name }}</td>
                            <td class="py-3 px-4 text-center font-bold text-blue-600">{{ $carrier->code ?? '-' }}</td>
                            
                            <!-- Tombol Hapus Hanya untuk Admin -->
                            @if(Auth::user()->role === 'admin')
                            <td class="py-3 px-4 text-center">
                                <form action="{{ route('carriers.destroy', $carrier->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ekspedisi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1.5 rounded text-xs hover:bg-red-400 transition shadow-sm">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-gray-500 italic">Belum ada data ekspedisi kargo.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>