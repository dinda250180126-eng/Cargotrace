<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    // Abaikan baris ini jika kamu sudah menggunakan $fillable
    protected $guarded = [];

    // 1. Relasi ke Ekspedisi (Tabel carriers)
    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
    }

    // 2. Relasi ke Lokasi Asal (Tabel locations)
    public function origin()
    {
        return $this->belongsTo(Location::class, 'origin_id');
    }

    // 3. Relasi ke Lokasi Tujuan (Tabel locations)
    public function destination()
    {
        return $this->belongsTo(Location::class, 'destination_id');
    }

    // 4. Relasi ke Pemilik Resi (Tabel users)
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}