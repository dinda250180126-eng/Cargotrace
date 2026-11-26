<?php

namespace App\Services;

use App\Models\Shipment;
use Carbon\Carbon;

class EtaService
{
    /**
     * Menghitung status delay berdasarkan expected_arrival dan estimated_arrival.
     */
    public function calculateDelay(Shipment $shipment)
    {
        // Pastikan tabel shipments memiliki kolom 'expected_arrival' dan 'estimated_arrival'
        if (!$shipment->expected_arrival || !$shipment->estimated_arrival) {
            return [
                'status' => 'UNKNOWN',
                'delay_hours' => 0,
                'message' => 'Data tanggal kedatangan belum lengkap.'
            ];
        }

        $expected = Carbon::parse($shipment->expected_arrival);
        $estimated = Carbon::parse($shipment->estimated_arrival);

        // Jika estimasi melewati batas waktu yang diharapkan (Delay)
        if ($estimated->greaterThan($expected)) {
            $delayHours = $expected->diffInHours($estimated);
            $severity = $this->determineSeverity($delayHours);

            return [
                'status' => 'DELAYED',
                'delay_hours' => $delayHours,
                'severity' => $severity,
                'message' => "Pengiriman terlambat selama {$delayHours} jam."
            ];
        }

        // Jika tepat waktu atau lebih cepat
        return [
            'status' => 'ON_TIME',
            'delay_hours' => 0,
            'severity' => 'NONE',
            'message' => 'Pengiriman berjalan sesuai jadwal.'
        ];
    }

    /**
     * Menentukan tingkat keparahan (severity) berdasarkan durasi delay (Sesuai Slide 8)
     */
    private function determineSeverity(int $hours): string
    {
        if ($hours > 0 && $hours <= 6) {
            return 'LOW'; // 0-6 jam
        } elseif ($hours > 6 && $hours <= 24) {
            return 'MEDIUM'; // >6-24 jam
        } else {
            return 'HIGH'; // >24 jam
        }
    }
}