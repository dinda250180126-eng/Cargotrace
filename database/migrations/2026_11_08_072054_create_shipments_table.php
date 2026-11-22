<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique(); // Nomor Resi
            
            // Relasi ke tabel lain (Foreign Keys)
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('carrier_id')->constrained('carriers')->onDelete('cascade');
            $table->foreignId('origin_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('destination_id')->constrained('locations')->onDelete('cascade');
            
            // Detail Barang
            $table->string('cargo_type'); // Skenario kita: "K-Pop Merchandise"
            $table->decimal('weight', 8, 2); // Berat dalam satuan kg
            
            // Jadwal
            $table->dateTime('expected_departure'); // Jadwal berangkat
            $table->dateTime('expected_arrival'); // Perkiraan sampai (untuk diadu dengan ETA)
            
            // Status pengiriman (sesuai aturan alur bisnis wajib di proyek)
            $table->enum('status', [
                'CREATED', 
                'BOOKED', 
                'PICKED_UP', 
                'IN_TRANSIT', 
                'DELIVERED'
            ])->default('CREATED');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};