<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CarrierController extends Controller
{
    // GET /api/carriers
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => [] // Kosongkan dulu jika tabel belum ada, yang penting API jalan
        ], 200);
    }

    // POST /api/carriers
    public function store(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Carrier created successfully (Mock)',
            'data' => $request->all()
        ], 201);
    }
}