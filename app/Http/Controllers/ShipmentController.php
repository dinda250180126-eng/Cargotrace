<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'customer') {
            $shipments = Shipment::with(['origin', 'destination'])
                ->where('customer_id', Auth::id())
                ->get();
        } else {
            $shipments = Shipment::with(['origin', 'destination'])->get();
        }

        return view('shipments.index', compact('shipments'));
    }

    public function track(Request $request)
    {
        $trackingNumber = trim((string) $request->input('tracking_number'));

        if ($trackingNumber === '') {
            return redirect()->back()->with('error', 'Masukkan nomor resi terlebih dahulu.');
        }

        $shipment = Shipment::where('tracking_number', $trackingNumber)->first();

        if (! $shipment) {
            return redirect()->back()->with('error', "Nomor resi '{$trackingNumber}' tidak ditemukan.");
        }

        if (Auth::user()->role === 'customer' && $shipment->customer_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melacak resi tersebut.');
        }

        return redirect()->route('shipments.show', $shipment);
    }

    public function create()
    {
        if (Auth::user()->role === 'customer') {
            abort(403, 'Unauthorized action.');
        }

        $carriers = Carrier::all();
        $locations = Location::select('id', 'name')->get();
        $customers = User::where('role', 'customer')->get();

        return view('shipments.create', compact('carriers', 'locations', 'customers'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'customer') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $this->validateShipment($request);
        $shipment = Shipment::create($validated);

        AuditLog::create([
            'user_name' => Auth::user()->name,
            'action' => 'CREATE',
            'description' => 'Membuat resi pengiriman global baru: ' . $shipment->tracking_number,
        ]);

        return redirect('/shipments')->with('success', 'Resi baru berhasil dibuat!');
    }

    public function show(Shipment $shipment)
    {
        if (Auth::user()->role === 'customer' && $shipment->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized action. You can only view your own shipments.');
        }

        return view('shipments.show', compact('shipment'));
    }

    public function destroy(Shipment $shipment)
    {
        if (Auth::user()->role === 'customer') {
            abort(403, 'Unauthorized action.');
        }

        $trackingNumber = $shipment->tracking_number;
        $shipment->delete();

        AuditLog::create([
            'user_name' => Auth::user()->name,
            'action' => 'DELETE',
            'description' => 'Menghapus data resi pengiriman: ' . $trackingNumber,
        ]);

        return redirect('/shipments')->with('success', 'Data resi berhasil dihapus.');
    }

    public function edit(Shipment $shipment)
    {
        if (Auth::user()->role === 'customer') {
            abort(403, 'Unauthorized action.');
        }

        $carriers = Carrier::all();
        $locations = Location::select('id', 'name')->get();
        $customers = User::where('role', 'customer')->get();

        return view('shipments.edit', compact('shipment', 'carriers', 'locations', 'customers'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        if (Auth::user()->role === 'customer') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $this->validateShipment($request, $shipment->id);
        $shipment->update($validated);

        AuditLog::create([
            'user_name' => Auth::user()->name,
            'action' => 'UPDATE',
            'description' => 'Memperbarui status atau data resi pengiriman: ' . $shipment->tracking_number,
        ]);

        return redirect('/shipments')->with('success', 'Data resi berhasil diperbarui!');
    }

    /**
     * Validate shipment payload for store and update.
     */
    private function validateShipment(Request $request, ?int $ignoreId = null): array
    {
        $uniqueRule = $ignoreId
            ? 'required|unique:shipments,tracking_number,' . $ignoreId
            : 'required|unique:shipments';

        return $request->validate([
            'tracking_number' => $uniqueRule,
            'customer_id' => 'required|exists:users,id',
            'carrier_id' => 'nullable|exists:carriers,id',
            'status' => 'required|in:CREATED,BOOKED,PICKED_UP,IN_TRANSIT,DELIVERED',
            'weight' => 'required|numeric',
            'origin_id' => 'required|exists:locations,id',
            'destination_id' => 'required|exists:locations,id',
            'cargo_type' => 'required|string',
            'expected_departure' => 'required|date',
            'expected_arrival' => 'required|date',
            'exception_note' => 'nullable|string',
        ]);
    }
}
