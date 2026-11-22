<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_exceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');
            
            // Jenis masalah wajib sesuai dokumen UAS
            $table->enum('type', [
                'CUSTOMS_HOLD', 
                'WEATHER_DELAY', 
                'PORT_CONGESTION', 
                'DAMAGED', 
                'MISROUTED', 
                'DOCUMENTATION_ISSUE'
            ]);
            
            $table->enum('severity', ['LOW', 'MEDIUM', 'HIGH']); // Keparahan
            $table->enum('status', ['OPEN', 'INVESTIGATING', 'RESOLVED'])->default('OPEN');
            
            $table->text('description')->nullable();
            $table->timestamp('resolved_at')->nullable(); // Waktu masalah selesai
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_exceptions');
    }
};