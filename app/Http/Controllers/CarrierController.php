<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrier;
use Illuminate\Support\Facades\Auth;

class CarrierController extends Controller
{
    public function index()
    {
        $carriers = Carrier::latest()->get();
        return view('carriers.index', compact('carriers'));
    }

   public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $request->validate([
            'name' => 'required|string|max:255|unique:carriers,name',
            'code' => 'required|string|max:10' // <-- Tambahan validasi kode
        ]);

        // Simpan nama dan kodenya
        Carrier::create([
            'name' => $request->name,
            'code' => $request->code
        ]);

        return redirect('/carriers')->with('success', 'Maskapai logistik baru berhasil ditambahkan!');
    }

    public function destroy(Carrier $carrier)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $carrier->delete();

        return redirect('/carriers')->with('success', 'Data maskapai berhasil dihapus.');
    }
}