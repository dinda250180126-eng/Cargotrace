<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiSecurityTest extends TestCase
{
    /**
     * Memastikan API daftar resi tidak bisa dibuka tanpa token.
     */
    public function test_api_shipments_requires_authentication(): void
    {
        // Mencoba mengakses API tanpa token login
        $response = $this->getJson('/api/shipments');

        // Memastikan sistem menolak dengan status 401 (Unauthorized)
        $response->assertStatus(401);
    }

    /**
     * Memastikan API lokasi/bandara tidak bisa dibuka tanpa token.
     */
    public function test_api_locations_requires_authentication(): void
    {
        // Mencoba mengakses API tanpa token login
        $response = $this->getJson('/api/locations');

        // Memastikan sistem menolak dengan status 401 (Unauthorized)
        $response->assertStatus(401);
    }
}