<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-violet-700 to-fuchsia-600">
                Edit Shipment: {{ $shipment->tracking_number }}
            </h2>
            <a href="/shipments" class="bg-white/50 backdrop-blur text-gray-700 px-5 py-2 rounded-full shadow-sm border border-gray-200 hover:bg-white transition-all font-bold text-sm">
                &larr; Back
            </a>
        </div>
    </x-slot>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single { height: 46px; border-color: #e5e7eb; border-radius: 0.75rem; padding-top: 8px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { top: 10px; }
    </style>

    <!-- BACKGROUND UNGU KILAT -->
    <div class="relative py-12 min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-fuchsia-800 overflow-hidden">
        
        <!-- Efek Cahaya Neon -->
        <div class="absolute top-0 left-10 w-96 h-96 bg-fuchsia-500 rounded-full mix-blend-screen filter blur-[120px] opacity-50 animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-cyan-500 rounded-full mix-blend-screen filter blur-[120px] opacity-40"></div>

        <div class="relative max-w-4xl mx-auto sm:px-6 lg:px-8 z-10">
            
            <!-- Form Container (Glassmorphism) -->
            <div class="bg-white/90 backdrop-blur-xl shadow-2xl shadow-fuchsia-900/50 sm:rounded-[2rem] p-8 border border-white/40">
                
                @if ($errors->any())
                    <div class="bg-red-500/10 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-xl mb-6 shadow-sm">
                        <strong class="font-bold">Input Error:</strong>
                        <ul class="list-disc pl-5 mt-2">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="/shipments/{{ $shipment->id }}" autocomplete="off" class="space-y-6">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-extrabold text-sm uppercase tracking-wider mb-2">Tracking Number</label>
                            <input type="text" name="tracking_number" value="{{ old('tracking_number', $shipment->tracking_number) }}" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 bg-gray-50/50 py-3" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-extrabold text-sm uppercase tracking-wider mb-2">Customer Name</label>
                            <select name="customer_id" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 py-3" required>
                                <option value="">-- Select Customer --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $shipment->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-extrabold text-sm uppercase tracking-wider mb-2">Departure Schedule</label>
                            <input type="datetime-local" name="expected_departure" value="{{ old('expected_departure', $shipment->expected_departure) }}" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 py-3" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-extrabold text-sm uppercase tracking-wider mb-2">Estimated Arrival</label>
                            <input type="datetime-local" name="expected_arrival" value="{{ old('expected_arrival', $shipment->expected_arrival) }}" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 py-3" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 bg-indigo-50/50 rounded-2xl border border-indigo-50">
                        <div>
                            <label class="block text-indigo-900 font-extrabold text-sm uppercase tracking-wider mb-2">Origin Airport</label>
                            <select name="origin_id" class="w-full border-gray-200 rounded-xl shadow-sm searchable-select" required>
                                @foreach ($locations as $location) <option value="{{ $location->id }}" {{ $shipment->origin_id == $location->id ? 'selected' : '' }}>{{ $location->name }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-indigo-900 font-extrabold text-sm uppercase tracking-wider mb-2">Destination Airport</label>
                            <select name="destination_id" class="w-full border-gray-200 rounded-xl shadow-sm searchable-select" required>
                                @foreach ($locations as $location) <option value="{{ $location->id }}" {{ $shipment->destination_id == $location->id ? 'selected' : '' }}>{{ $location->name }}</option> @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-extrabold text-sm uppercase tracking-wider mb-2">Cargo Type / Nama Barang</label>
                        <input type="text" name="cargo_type" value="{{ old('cargo_type', $shipment->cargo_type) }}" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 py-3" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-extrabold text-sm uppercase tracking-wider mb-2">Shipment Status</label>
                            <select name="status" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 py-3 font-bold text-indigo-700" required>
                                <option value="CREATED" {{ $shipment->status == 'CREATED' ? 'selected' : '' }}>Order Created</option>
                                <option value="BOOKED" {{ $shipment->status == 'BOOKED' ? 'selected' : '' }}>Flight Booked</option>
                                <option value="PICKED_UP" {{ $shipment->status == 'PICKED_UP' ? 'selected' : '' }}>Picked Up</option>
                                <option value="IN_TRANSIT" {{ $shipment->status == 'IN_TRANSIT' ? 'selected' : '' }}>In Transit (Flying)</option>
                                <option value="DELIVERED" {{ $shipment->status == 'DELIVERED' ? 'selected' : '' }}>Delivered to Destination</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-extrabold text-sm uppercase tracking-wider mb-2">Weight (Kg)</label>
                            <input type="number" step="0.1" name="weight" value="{{ old('weight', $shipment->weight) }}" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 py-3" required>
                        </div>
                    </div>

                    <!-- KOTAK EXCEPTION -->
                    <div class="p-5 bg-red-500/10 border border-red-200 rounded-2xl">
                        <label class="block text-red-700 font-extrabold text-sm uppercase tracking-wider mb-2 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span> Exception Note (Opsional)
                        </label>
                        <input type="text" name="exception_note" value="{{ old('exception_note', $shipment->exception_note) }}" class="w-full border-red-200 rounded-xl shadow-sm focus:border-red-500 focus:ring-red-500 py-3 bg-white/80" placeholder="Isi jika ada kendala (misal: Delay, Bea Cukai)...">
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <div class="pt-4 border-t border-gray-100 flex justify-end gap-4">
                        <a href="/shipments" class="px-6 py-3 text-gray-600 font-bold hover:text-gray-900 transition">Cancel</a>
                        <button type="submit" class="bg-gradient-to-r from-violet-600 to-fuchsia-500 text-white px-8 py-3 rounded-full shadow-lg shadow-fuchsia-500/30 hover:scale-105 hover:shadow-xl transition-all font-bold tracking-wide">
                            Update Shipment Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() { $('.searchable-select').select2({ placeholder: "Search airport...", allowClear: true, width: '100%' }); });
    </script>
</x-app-layout>