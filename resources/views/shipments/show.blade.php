<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 tracking-tight">
                Shipment Details: <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">{{ $shipment->tracking_number }}</span>
            </h2>
            <a href="/shipments" class="bg-white text-gray-700 px-5 py-2 rounded-full shadow-sm border border-gray-200 hover:bg-gray-50 hover:shadow-md transition-all duration-300 text-sm font-bold flex items-center gap-2">
                &larr; Back to List
            </a>
        </div>
    </x-slot>

    <!-- Memanggil CSS Leaflet untuk Peta -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        /* Ubah radius peta jadi lebih melengkung ala modern UI */
        #route-map { height: 420px; width: 100%; border-radius: 1.25rem; z-index: 1; }
        .plane-marker { font-size: 28px; text-shadow: 0px 4px 10px rgba(79, 70, 229, 0.6); animation: float 3s ease-in-out infinite; }
        @keyframes float { 0% { transform: translateY(0px) scale(1); } 50% { transform: translateY(-8px) scale(1.1); } 100% { transform: translateY(0px) scale(1); } }
        .distance-label {
            background: linear-gradient(to right, #4f46e5, #3b82f6) !important; color: white !important; border: none !important;
            border-radius: 12px !important; font-size: 13px; font-weight: bold; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4) !important;
            padding: 6px 12px !important;
        }
        .distance-label::before { border-bottom-color: #4f46e5 !important; }
    </style>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- SECTION 1: Peta Pelacakan Rute (Apple Widget Style) -->
            <div class="bg-white/80 backdrop-blur-xl overflow-hidden shadow-xl shadow-indigo-100/40 sm:rounded-[2rem] p-2 border border-white hover:shadow-2xl transition-shadow duration-500 relative">
                <div class="absolute top-6 left-8 z-10 bg-white/90 backdrop-blur px-4 py-2 rounded-2xl shadow-lg border border-gray-100">
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Live Tracking
                    </h3>
                </div>
                <div class="ring-4 ring-indigo-50/50 rounded-[1.25rem] relative overflow-hidden">
                    <div id="route-map"></div>
                </div>
            </div>

            <!-- SECTION 1.5: NOTIFIKASI EXCEPTION (Glassmorphism Merah) -->
            @if(!empty($shipment->exception_note))
            <div class="bg-red-500/10 backdrop-blur-md border border-red-200 p-6 rounded-3xl shadow-lg shadow-red-500/10 animate-pulse">
                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-red-100 p-2 rounded-full">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-black text-red-700 uppercase tracking-widest">Exception Alert</h3>
                        <div class="mt-1 text-base text-red-800 font-semibold">
                            <p>{{ $shipment->exception_note }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- SECTION 2: Data Status & ETA (Glassmorphism & Glow) -->
            <div class="bg-gradient-to-br from-white to-indigo-50/40 backdrop-blur-lg overflow-hidden shadow-xl shadow-indigo-100/50 sm:rounded-[2rem] p-8 border border-white hover:-translate-y-1 hover:shadow-2xl transition-all duration-500">
                <h3 class="text-lg font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-800 to-blue-600 mb-6 border-b border-indigo-100 pb-3">Shipment Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Kolom Kiri -->
                    <div class="space-y-6">
                        <div>
                            <span class="text-indigo-400 text-xs font-black uppercase tracking-widest">Current Status</span>
                            <p class="mt-2">
                                <span class="bg-gradient-to-r from-indigo-500 to-blue-500 text-white shadow-lg shadow-indigo-500/40 py-2 px-6 rounded-full text-sm font-bold tracking-wide ring-2 ring-white inline-block hover:scale-105 transition-transform">
                                    {{ $shipment->status }}
                                </span>
                            </p>
                        </div>
                        <div class="bg-white/60 p-4 rounded-2xl shadow-sm border border-gray-100">
                            <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Route</span>
                            <p class="font-black text-gray-800 text-lg mt-1 flex items-center gap-2">
                                {{ $shipment->origin->name ?? 'Unknown' }} 
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                {{ $shipment->destination->name ?? 'Unknown' }}
                            </p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Carrier</span>
                                <p class="font-bold text-gray-800 mt-1">{{ $shipment->carrier->name ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Cargo Weight</span>
                                <p class="font-bold text-gray-800 mt-1">{{ $shipment->cargo_type }} ({{ $shipment->weight }} Kg)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan (Timeline & ETA) -->
                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-[1.5rem] border border-indigo-50 shadow-sm relative overflow-hidden flex flex-col justify-center">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-400/10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
                        <h4 class="font-bold text-indigo-900 uppercase text-xs tracking-widest border-b border-indigo-50 pb-3 mb-4">Schedule & Estimated Arrival</h4>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center bg-gray-50/50 p-3 rounded-xl">
                                <span class="text-gray-500 text-xs font-bold uppercase tracking-wider">Departure</span>
                                <p class="font-bold text-gray-700 text-sm">{{ \Carbon\Carbon::parse($shipment->expected_departure)->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="flex justify-between items-center bg-emerald-50/50 p-3 rounded-xl border border-emerald-100">
                                <span class="text-emerald-600 text-xs font-bold uppercase tracking-wider">Arrival</span>
                                <p class="font-black text-emerald-600 text-lg">{{ \Carbon\Carbon::parse($shipment->expected_arrival)->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Riwayat Pelacakan (Gradient Timeline) -->
            <div class="bg-white/90 backdrop-blur-lg overflow-hidden shadow-xl shadow-gray-200/50 sm:rounded-[2rem] p-8 border border-white">
                <h3 class="text-lg font-extrabold text-gray-800 mb-8 pb-2">Tracking History</h3>
                
                <!-- Wadah Garis Vertikal (Gradien Warna) -->
                <div class="relative ml-3 md:ml-4 space-y-10 my-4 border-l-4 border-transparent" style="border-image: linear-gradient(to bottom, #4f46e5, #e5e7eb) 1;">
                    
                    <!-- Status 1 (Paling Baru - Titik Warna Biru/Aktif) -->
                    <div class="relative pl-8 group">
                        <div class="absolute -left-[11px] top-1.5 w-4 h-4 bg-indigo-600 rounded-full ring-4 ring-indigo-100 group-hover:scale-125 transition-transform duration-300"></div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start bg-white hover:bg-gray-50 p-4 rounded-2xl transition-colors duration-300 -mt-4 border border-transparent hover:border-gray-100">
                            <div>
                                <h4 class="text-base font-black text-gray-900">Arrived at Destination</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $shipment->destination->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-400 mt-2 font-medium flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    System Auto-Update
                                </p>
                            </div>
                            <div class="text-sm font-bold text-indigo-600 mt-3 sm:mt-0 whitespace-nowrap bg-indigo-50/80 px-4 py-1.5 rounded-full border border-indigo-100 shadow-sm">
                                {{ \Carbon\Carbon::parse($shipment->expected_arrival)->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- KOTAK BARU: Status Exception -->
                    @if(!empty($shipment->exception_note))
                    <div class="relative pl-8 group">
                        <div class="absolute -left-[11px] top-1.5 w-4 h-4 bg-red-500 rounded-full ring-4 ring-red-100 group-hover:scale-125 transition-transform duration-300 animate-pulse"></div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start bg-red-50/50 p-4 rounded-2xl -mt-4 border border-red-100">
                            <div>
                                <h4 class="text-base font-black text-red-600">Shipment Exception / Issue</h4>
                                <p class="text-sm text-gray-800 font-bold mt-1">{{ $shipment->exception_note }}</p>
                            </div>
                            <div class="text-sm font-bold text-red-600 mt-3 sm:mt-0 whitespace-nowrap bg-white px-4 py-1.5 rounded-full border border-red-100 shadow-sm">
                                {{ $shipment->updated_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Status 2 (Riwayat Lama) -->
                    <div class="relative pl-8 group">
                        <div class="absolute -left-[11px] top-1.5 w-4 h-4 bg-gray-300 rounded-full ring-4 ring-white group-hover:bg-indigo-300 transition-colors duration-300"></div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start -mt-2">
                            <div>
                                <h4 class="text-base font-bold text-gray-600">Departed from Origin</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ $shipment->origin->name ?? 'Unknown' }}</p>
                            </div>
                            <div class="text-sm font-semibold text-gray-400 mt-2 sm:mt-0 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($shipment->expected_departure)->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Status 3 (Riwayat Paling Awal) -->
                    <div class="relative pl-8 group">
                        <div class="absolute -left-[11px] top-1.5 w-4 h-4 bg-gray-200 rounded-full ring-4 ring-white"></div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start -mt-2">
                            <div>
                                <h4 class="text-base font-bold text-gray-500">Shipment Booked (Manifested)</h4>
                                <p class="text-sm text-gray-400 mt-1">Electronic shipment information has been received.</p>
                            </div>
                            <div class="text-sm font-semibold text-gray-400 mt-2 sm:mt-0 whitespace-nowrap">
                                {{ $shipment->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Script jQuery & Leaflet (Logika Peta Tetap Asli) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        $(document).ready(function() {
            var rawOrigin = "{!! $shipment->origin->name ?? '' !!}";
            var rawDest = "{!! $shipment->destination->name ?? '' !!}";
            
            var originName = rawOrigin ? rawOrigin.split(' (')[0] : '';
            var destName = rawDest ? rawDest.split(' (')[0] : '';

            var map = L.map('route-map').setView([20.0, 0.0], 2);
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, TomTom, 2012'
            }).addTo(map);

            var originLatLng = null;
            var destLatLng = null;

            function drawRoute() {
                if (originLatLng && destLatLng) {
                    var flightLine = L.polyline([originLatLng, destLatLng], {
                        color: '#4f46e5', weight: 4, dashArray: '10, 15', opacity: 0.8
                    }).addTo(map);

                    var distanceMeters = map.distance(L.latLng(originLatLng), L.latLng(destLatLng));
                    var distanceKm = (distanceMeters / 1000).toLocaleString('en-US', { maximumFractionDigits: 0 });

                    var midLat = (originLatLng[0] + destLatLng[0]) / 2;
                    var midLng = (originLatLng[1] + destLatLng[1]) / 2;

                    var planeMarker = L.marker([midLat, midLng], {
                        icon: L.divIcon({ className: 'plane-marker', html: '<div>✈️</div>', iconSize: [30, 30], iconAnchor: [15, 15] })
                    }).addTo(map);

                    planeMarker.bindTooltip(
                        "<div class='text-center'><span class='text-white/80 text-[10px] tracking-widest uppercase'>Flight Distance</span><br/><span class='font-black text-lg shadow-sm'>" + distanceKm + " km</span></div>", 
                        { permanent: true, direction: 'bottom', className: 'distance-label', offset: [0, 5] }
                    ).openTooltip();

                    map.fitBounds(flightLine.getBounds(), { padding: [50, 50] });
                }
            }

            if (originName) {
                $.getJSON('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(originName), function(data) {
                    if (data && data.length > 0) {
                        originLatLng = [parseFloat(data[0].lat), parseFloat(data[0].lon)];
                        L.marker(originLatLng).addTo(map).bindPopup(`<div class="font-bold text-indigo-700">Origin:</div>${originName}`).openPopup();
                        drawRoute();
                    }
                });
            }

            if (destName) {
                $.getJSON('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(destName), function(data) {
                    if (data && data.length > 0) {
                        destLatLng = [parseFloat(data[0].lat), parseFloat(data[0].lon)];
                        L.marker(destLatLng).addTo(map).bindPopup(`<div class="font-bold text-indigo-700">Destination:</div>${destName}`).openPopup();
                        drawRoute();
                    }
                });
            }
        });
    </script>
</x-app-layout>