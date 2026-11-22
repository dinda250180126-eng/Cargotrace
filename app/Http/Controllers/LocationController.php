<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location; // Memanggil tabel locations

class LocationController extends Controller
{
    public function index(Request $request)
    {
        // Menangkap kata kunci yang diketik user
        $search = $request->input('search');

        // Jika user melakukan pencarian
        if ($search) {
            $locations = Location::where('name', 'LIKE', "%{$search}%")
                                 ->orWhere('country_code', 'LIKE', "%{$search}%")
                                 ->paginate(20); // Tampilkan 20 data per halaman
        } else {
            // Jika tidak ada pencarian, tampilkan data secara standar
            $locations = Location::paginate(20);
        }

        return view('locations.index', compact('locations'));
    }
}