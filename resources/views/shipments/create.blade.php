<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create New International Shipment
        </h2>
    </x-slot>

    <!-- Memanggil CSS untuk Select2 (Pencarian) dan Leaflet (Peta) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        .select2-container .select2-selection--single { height: 42px; border-color: #d1d5db; border-radius: 0.375rem; padding-top: 6px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { top: 8px; }
        #world-map { height: 350px; width: 100%; border-radius: 0.5rem; z-index: 1; background-color: #aacbdf; } /* Warna dasar laut */

        .plane-marker { font-size: 26px; text-shadow: 2px 2px 4px rgba(0,0,0,0.4); animation: pulse 2s infinite; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.2); } 100% { transform: scale(1); } }

        .distance-label {
            background-color: #4f46e5 !important; color: white !important; border: none !important;
            border-radius: 8px !important; font-size: 13px; box-shadow: 0 4px 6px rgba(0,0,0,0.2) !important;
        }
        .distance-label::before { border-bottom-color: #4f46e5 !important; }
    </style>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <strong class="font-bold">Input Error:</strong>
                        <ul class="list-disc pl-5 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- PETA INTERAKTIF DUNIA -->
                <div class="mb-6 border-2 border-gray-200 rounded-lg shadow-inner relative">
                    <div id="world-map"></div>
                </div>

                <form method="POST" action="/shipments" autocomplete="off">
                    @csrf

                    <!-- 1. Tracking Number & Customer Name -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Tracking Number</label>
                            <input type="text" name="tracking_number" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="e.g., KOR-JPN-001">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Customer Name</label>
                            <select name="customer_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">-- Select Customer --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- 2. Schedule -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Departure Schedule</label>
                            <input type="datetime-local" name="expected_departure" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Estimated Arrival</label>
                            <input type="datetime-local" name="expected_arrival" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                    </div>

                    <!-- 3. Locations (KEDUANYA BISA DICARI SECARA BEBAS) -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Origin Airport</label>
                            <select name="origin_id" id="origin_id" class="w-full border-gray-300 rounded-md shadow-sm searchable-select" required>
                                <option value="">-- Select Origin --</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Destination Airport</label>
                            <select name="destination_id" id="destination_id" class="w-full border-gray-300 rounded-md shadow-sm searchable-select" required>
                                <option value="">-- Select Destination --</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- 4. Cargo -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Cargo Type / Nama Barang</label>
                        <input type="text" name="cargo_type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Ketik nama barang (contoh: Pakaian, Sepatu, Dokumen)">
                    </div>

                    <!-- 5. Status & Weight -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Shipment Status</label>
                            <select name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="CREATED">Order Created</option>
                                <option value="BOOKED">Flight Booked</option>
                                <option value="PICKED_UP">Picked Up</option>
                                <option value="IN_TRANSIT">In Transit (Flying)</option>
                                <option value="DELIVERED">Delivered to Destination</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Weight (Kg)</label>
                            <input type="number" step="0.1" name="weight" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="0.0">
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex items-center gap-4 mt-2">
                        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded shadow hover:bg-indigo-500 transition font-bold text-lg">
                            Save Global Shipment
                        </button>
                        <a href="/shipments" class="text-gray-600 hover:text-gray-900 transition font-medium">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Script untuk jQuery, Select2, dan Mesin Peta Leaflet -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        $(document).ready(function() {
            // Aktifkan Select2 untuk SEMUA kotak pencarian (Origin dan Destination)
            $('.searchable-select').select2({
                placeholder: "Type to search global airports...",
                allowClear: true,
                width: '100%'
            });

            // INISIALISASI PETA DUNIA SEMPURNA
            var map = L.map('world-map', {
                center: [20.0, 0.0],
                zoom: 2,
                minZoom: 2, 
                maxBounds: [
                    [-90, -180], 
                    [90, 180]    
                ],
                maxBoundsViscosity: 1.0 
            });

            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri',
                noWrap: true 
            }).addTo(map);

            var originPin, destPin, flightLine, planeMarker;
            var originLatLng = null;
            var destLatLng = null;

            function drawFlightRoute() {
                if (flightLine) map.removeLayer(flightLine);
                if (planeMarker) map.removeLayer(planeMarker);

                if (originLatLng && destLatLng) {
                    
                    flightLine = L.polyline([originLatLng, destLatLng], {
                        color: '#4f46e5',
                        weight: 3,
                        dashArray: '10, 10',
                        opacity: 0.8
                    }).addTo(map);

                    var distanceMeters = map.distance(L.latLng(originLatLng), L.latLng(destLatLng));
                    var distanceKm = (distanceMeters / 1000).toLocaleString('en-US', { maximumFractionDigits: 0 });

                    var midLat = (originLatLng[0] + destLatLng[0]) / 2;
                    var midLng = (originLatLng[1] + destLatLng[1]) / 2;

                    planeMarker = L.marker([midLat, midLng], {
                        icon: L.divIcon({
                            className: 'plane-marker',
                            html: '<div>✈️</div>',
                            iconSize: [30, 30],
                            iconAnchor: [15, 15]
                        })
                    }).addTo(map);

                    planeMarker.bindTooltip(
                        "<div class='text-center'><span class='text-gray-200 text-xs font-normal tracking-wide uppercase'>Flight Distance</span><br/><span class='font-bold text-lg'>" + distanceKm + " km</span></div>", 
                        { permanent: true, direction: 'bottom', className: 'distance-label', offset: [0, 5] }
                    ).openTooltip();

                    map.fitBounds(flightLine.getBounds(), { padding: [50, 50] });
                } else if (originLatLng) {
                    map.flyTo(originLatLng, 4);
                } else if (destLatLng) {
                    map.flyTo(destLatLng, 4);
                }
            }

            function flyToAirport(airportName, isOrigin) {
                if (!airportName || airportName.includes('-- Select')) {
                    if (isOrigin) { originLatLng = null; if(originPin) map.removeLayer(originPin); }
                    else { destLatLng = null; if(destPin) map.removeLayer(destPin); }
                    drawFlightRoute();
                    return;
                }
                
                var cleanAirportName = airportName.split(' (')[0];
                
                $.getJSON('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(cleanAirportName), function(data) {
                    if (data && data.length > 0) {
                        var lat = parseFloat(data[0].lat);
                        var lon = parseFloat(data[0].lon);
                        var coords = [lat, lon];

                        if (isOrigin) {
                            if (originPin) map.removeLayer(originPin);
                            originLatLng = coords;
                            originPin = L.marker(coords).addTo(map).bindPopup(`<b>Origin:</b><br>${cleanAirportName}`).openPopup();
                        } else {
                            if (destPin) map.removeLayer(destPin);
                            destLatLng = coords;
                            destPin = L.marker(coords).addTo(map).bindPopup(`<b>Destination:</b><br>${cleanAirportName}`).openPopup();
                        }
                        
                        drawFlightRoute();
                    }
                });
            }

            // PETA SEKARANG AKAN MERESPON SAAT ORIGIN ATAU DESTINATION DIUBAH
            $('#origin_id').on('change', function() { flyToAirport($(this).find("option:selected").text(), true); });
            $('#destination_id').on('change', function() { flyToAirport($(this).find("option:selected").text(), false); });

            setTimeout(function() {
                map.invalidateSize(); 
            }, 800);
        });
    </script>
</x-app-layout>