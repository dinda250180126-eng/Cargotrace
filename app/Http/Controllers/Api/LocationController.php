<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    // GET /api/locations
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => Location::paginate(20)
        ], 200);
    }

    // POST /api/locations
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:locations',
        ]);

        $location = Location::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Location created successfully',
            'data' => $location
        ], 201);
    }
}