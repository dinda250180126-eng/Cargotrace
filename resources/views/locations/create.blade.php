<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-black text-3xl tracking-tight text-blue-900 drop-shadow-sm flex items-center gap-3">
                <span class="text-3xl">✈️</span> Tambah Lokasi Bandara Baru
            </h2>
            <a href="/locations" class="bg-white text-gray-700 px-5 py-2 rounded-full shadow-sm border border-gray-200 hover:bg-gray-50 transition-all font-bold text-sm">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8 min-h-screen bg-slate-50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-gray-200/50 rounded-[2rem] border border-gray-100 p-8">
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 p-4 rounded-xl mb-6">
                        <ul class="list-disc pl-5 text-red-700 font-bold text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/locations" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-gray-700 font-black text-xs uppercase tracking-wider mb-2">Nama Bandara</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               placeholder="Contoh: Kualanamu International Airport (KNO) - Indonesia"
                               class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 font-semibold">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-black text-xs uppercase tracking-wider mb-2">Kode Negara</label>
                        <input type="text" name="country_code" value="{{ old('country_code') }}" required maxlength="10"
                               placeholder="Contoh: ID, KR, JP, SG"
                               class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 font-semibold uppercase">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-black text-xs uppercase tracking-wider mb-2">Latitude (Opsional)</label>
                            <input type="number" step="any" name="latitude" value="{{ old('latitude') }}"
                                   placeholder="Contoh: 3.6422"
                                   class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-black text-xs uppercase tracking-wider mb-2">Longitude (Opsional)</label>
                            <input type="number" step="any" name="longitude" value="{{ old('longitude') }}"
                                   placeholder="Contoh: 98.8853"
                                   class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end gap-4">
                        <a href="/locations" class="px-6 py-3 text-gray-600 font-bold hover:text-gray-900 transition">Batal</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-black px-8 py-3 rounded-full shadow-lg transition-all">
                            Simpan Bandara
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
