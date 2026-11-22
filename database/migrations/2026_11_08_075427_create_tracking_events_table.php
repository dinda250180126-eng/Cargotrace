<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');
            
            $table->string('status'); // Status saat event terjadi
            $table->string('location_name')->nullable(); // Nama tempat saat itu
            $table->decimal('latitude', 10, 8)->nullable(); // Koordinat peta
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('description')->nullable(); // Penjelasan (misal: "Barang transit di Singapura")
            
            // Mencatat user (operator) yang menginput riwayat ini
            $table->foreignId('created_by')->constrained('users'); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_events');
    }
};