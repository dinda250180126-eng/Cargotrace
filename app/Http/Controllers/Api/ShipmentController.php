<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Services\TrackingService;
use App\Services\EtaService;

class ShipmentController extends Controller
{
    // GET /api/shipments (Menampilkan semua data dengan Pagination)
    public function index()
    {
        // Mengambil data shipment beserta relasi lokasi (origin & destination), dibatasi 10 per halaman
        $shipments = Shipment::with(['origin', 'destination'])->paginate(10);
        
        return response()->json([
            'status' => 'success',
            'data' => $shipments
        ], 200);
    }

    // POST /api/shipments (Membuat resi baru)
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'tracking_number' => 'required|string|unique:shipments',
                'origin_id' => 'required|exists:locations,id',
                'destination_id' => 'required|exists:locations,id',
                'status' => 'required|string',
                'weight' => 'nullable|numeric',
            ]);

            $shipment = Shipment::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Shipment created successfully',
                'data' => $shipment
            ], 201); // 201 Created

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    // GET /api/shipments/{id} (Melihat detail 1 resi)
    public function show($id)
    {
        $shipment = Shipment::with(['origin', 'destination'])->find($id);

        if (!$shipment) {
            return response()->json(['status' => 'error', 'message' => 'Shipment not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $shipment
        ], 200);
    }

    // PUT/PATCH /api/shipments/{id} (Mengupdate data resi)
    public function update(Request $request, $id)
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return response()->json(['status' => 'error', 'message' => 'Shipment not found'], 404);
        }

        try {
            $validated = $request->validate([
                'status' => 'sometimes|required|string',
                'weight' => 'sometimes|required|numeric',
            ]);

            $shipment->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Shipment updated successfully',
                'data' => $shipment
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    // DELETE /api/shipments/{id} (Menghapus resi)
    public function destroy($id)
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return response()->json(['status' => 'error', 'message' => 'Shipment not found'], 404);
        }

        $shipment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Shipment deleted successfully'
        ], 200);
    }

    // GET /api/shipments/{id}/tracking (Melihat riwayat pelacakan)
    public function getTracking($id)
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return response()->json(['status' => 'error', 'message' => 'Shipment not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'tracking_number' => $shipment->tracking_number,
                'current_status' => $shipment->status,
                'updated_at' => $shipment->updated_at
            ]
        ], 200);
    }

    // POST /api/shipments/{id}/tracking (Menambah riwayat/update status pelacakan)
    public function updateTracking(Request $request, $id, TrackingService $trackingService)
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return response()->json(['status' => 'error', 'message' => 'Shipment not found'], 404);
        }

        $validated = $request->validate([
            'status' => 'required|string',
            'description' => 'nullable|string'
        ]);

        try {
            // Memanggil Business Logic dari Service Layer
            $updatedShipment = $trackingService->updateTrackingStatus(
                $shipment, 
                $validated['status'], 
                $validated['description'] ?? null
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Tracking status updated',
                'data' => $updatedShipment
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    // GET /api/shipments/{id}/eta (Mengecek status ETA & Delay)
    public function checkEta($id, EtaService $etaService)
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return response()->json(['status' => 'error', 'message' => 'Shipment not found'], 404);
        }

        // Memanggil Business Logic dari EtaService
        $delayStatus = $etaService->calculateDelay($shipment);

        return response()->json([
            'status' => 'success',
            'data' => [
                'tracking_number' => $shipment->tracking_number,
                'expected_arrival' => $shipment->expected_arrival,
                'estimated_arrival' => $shipment->estimated_arrival,
                'delay_analysis' => $delayStatus
            ]
        ], 200);
    }
}