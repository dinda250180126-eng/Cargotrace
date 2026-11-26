<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExceptionController extends Controller
{
    // GET /api/shipments/{id}/exceptions
    public function index($id)
    {
        return response()->json([
            'status' => 'success',
            'shipment_id' => $id,
            'data' => [] 
        ], 200);
    }

    // POST /api/shipments/{id}/exceptions
    public function store(Request $request, $id)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Exception logged successfully for shipment ' . $id,
            'data' => $request->all()
        ], 201);
    }

    // PATCH /api/exceptions/{id}
    public function update(Request $request, $id)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Exception ' . $id . ' resolved successfully',
        ], 200);
    }
}