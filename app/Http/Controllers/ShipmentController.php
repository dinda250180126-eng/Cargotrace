<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\Carrier;
use App\Models\Location;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'customer') {
            $shipments = Shipment::where('customer_id', Auth::id())->get();
        } else {
            $shipments = Shipment::all();
        }
        return view('shipments.index', compact('shipments'));
    }

    public function create()
    {
        if (Auth::user()->role === 'customer') abort(403, 'Unauthorized action.');

        $carriers = Carrier::all();
        $locations = Location::all(); 
        $customers = User::where('role', 'customer')->get(); 

        return view('shipments.create', compact('carriers', 'locations', 'customers'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'customer') abort(403, 'Unauthorized action.');

        $request->validate([
            'tracking_number' => 'required|unique:shipments',
            'customer_id' => 'required|exists:users,id',
            'status' => 'required',
            'weight' => 'required|numeric',
            'origin_id' => 'required|integer',
            'destination_id' => 'required|integer',
            'cargo_type' => 'required|string',
            'expected_departure' => 'required|date',
            'expected_arrival' => 'required|date',
            'exception_note' => 'nullable|string'
        ]);

        $shipment = Shipment::create($request->all());

        AuditLog::create([
            'user_name' => Auth::user()->name,
            'action' => 'CREATE',
            'description' => 'Membuat resi pengiriman global baru: ' . $shipment->tracking_number
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
        if (Auth::user()->role === 'customer') abort(403, 'Unauthorized action.');

        $trackingNumber = $shipment->tracking_number;
        $shipment->delete();

        AuditLog::create([
            'user_name' => Auth::user()->name,
            'action' => 'DELETE',
            'description' => 'Menghapus data resi pengiriman: ' . $trackingNumber
        ]);

        return redirect('/shipments')->with('success', 'Data resi berhasil dihapus.');
    }

    public function edit(Shipment $shipment)
    {
        if (Auth::user()->role === 'customer') abort(403, 'Unauthorized action.');

        $carriers = Carrier::all();
        $locations = Location::all();
        $customers = User::where('role', 'customer')->get();

        return view('shipments.edit', compact('shipment', 'carriers', 'locations', 'customers'));
    }

    // INI ADALAH FUNGSI UPDATE YANG SEMPAT HILANG
    public function update(Request $request, Shipment $shipment)
    {
        if (Auth::user()->role === 'customer') abort(403, 'Unauthorized action.');

        $request->validate([
            'tracking_number' => 'required|unique:shipments,tracking_number,' . $shipment->id,
            'customer_id' => 'required|exists:users,id',
            'status' => 'required',
            'weight' => 'required|numeric',
            'origin_id' => 'required|integer',
            'destination_id' => 'required|integer',
            'cargo_type' => 'required|string',
            'expected_departure' => 'required|date',
            'expected_arrival' => 'required|date',
            'exception_note' => 'nullable|string'
        ]);

        $shipment->update($request->all());

        AuditLog::create([
            'user_name' => Auth::user()->name,
            'action' => 'UPDATE',
            'description' => 'Memperbarui status atau data resi pengiriman: ' . $shipment->tracking_number
        ]);

        return redirect('/shipments')->with('success', 'Data resi berhasil diperbarui!');
    }
}