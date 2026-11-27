<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $locations = Location::where('name', 'LIKE', "%{$search}%")
                ->orWhere('country_code', 'LIKE', "%{$search}%")
                ->paginate(20);
        } else {
            $locations = Location::paginate(20);
        }

        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        if (Auth::user()->role === 'customer') {
            abort(403, 'Unauthorized action.');
        }

        return view('locations.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'customer') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name',
            'country_code' => 'required|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $validated['country_code'] = strtoupper($validated['country_code']);
        $location = Location::create($validated);

        AuditLog::create([
            'user_name' => Auth::user()->name,
            'action' => 'CREATE',
            'description' => 'Menambahkan lokasi bandara baru: ' . $location->name,
        ]);

        return redirect('/locations')->with('success', 'Lokasi bandara baru berhasil ditambahkan!');
    }

    public function update(Request $request, Location $location)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name,' . $location->id,
            'country_code' => 'required|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $validated['country_code'] = strtoupper($validated['country_code']);
        $location->update($validated);

        return redirect('/locations')->with('success', 'Lokasi bandara berhasil diperbarui!');
    }

    public function destroy(Location $location)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        try {
            $name = $location->name;
            $location->delete();

            AuditLog::create([
                'user_name' => Auth::user()->name,
                'action' => 'DELETE',
                'description' => 'Menghapus lokasi bandara: ' . $name,
            ]);

            return redirect('/locations')->with('success', 'Lokasi bandara berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect('/locations')->withErrors(['error' => 'Lokasi bandara ini sedang dipakai oleh data pengiriman dan tidak dapat dihapus.']);
        }
    }
}
