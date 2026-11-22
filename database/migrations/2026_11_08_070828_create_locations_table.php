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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama lokasi, misal: Incheon Airport
            $table->string('country_code'); // Kode negara, misal: KR, ID
            $table->decimal('latitude', 10, 8)->nullable(); // Garis lintang untuk peta
            $table->decimal('longitude', 11, 8)->nullable(); // Garis bujur untuk peta
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};