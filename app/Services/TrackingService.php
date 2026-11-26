<?php

namespace App\Services;

use App\Models\Shipment;
use Exception;
use Illuminate\Validation\ValidationException;

class TrackingService
{
    /**
     * Memperbarui status shipment dan merekam jejak historisnya.
     */
    public function updateTrackingStatus(Shipment $shipment, string $newStatus, string $description = null)
    {
        // 1. Business Rule: Status Terminal tidak boleh diubah
        if ($shipment->status === 'DELIVERED') {
            throw ValidationException::withMessages([
                'status' => 'Pengiriman sudah DELIVERED dan tidak dapat diubah lagi.'
            ]);
        }

        // 2. Daftar Transisi Status yang Valid
        $validTransitions = [
            'CREATED'    => ['BOOKED', 'CANCELLED'],
            'BOOKED'     => ['PICKED_UP'],
            'PICKED_UP'  => ['IN_TRANSIT'],
            'IN_TRANSIT' => ['DELIVERED', 'DELAYED', 'EXCEPTION'],
            'DELAYED'    => ['IN_TRANSIT', 'DELIVERED'],
            'EXCEPTION'  => ['IN_TRANSIT', 'RETURNED'],
        ];

        $currentStatus = $shipment->status ?? 'CREATED';

        // 3. Validasi apakah lompatan status diizinkan[cite: 8]
        if (isset($validTransitions[$currentStatus]) && !in_array($newStatus, $validTransitions[$currentStatus])) {
            throw ValidationException::withMessages([
                'status' => "Transisi tidak valid: Tidak bisa berpindah dari {$currentStatus} ke {$newStatus}."
            ]);
        }

        // 4. Update status di tabel shipments[cite: 8]
        $shipment->update(['status' => $newStatus]);

        // 5. Simpan ke tabel historis tracking_events (Pastikan kamu sudah buat model & migrasi TrackingEvent)[cite: 8]
        /* 
        $shipment->trackingEvents()->create([
            'status' => $newStatus,
            'description' => $description ?? "Status diperbarui menjadi {$newStatus}",
            'user_id' => auth()->id(),
        ]);
        */

        return $shipment;
    }
}