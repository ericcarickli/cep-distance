<?php

namespace App\Repositories;

use App\Models\Distance;

class DistanceRepository
{
    public function getAllDistances()
    {
        return Distance::all();
    }

    public function saveDistance(string $cepFrom, string $cepTo, float $distance)
    {
        return Distance::create([
            'cep_from' => $cepFrom,
            'cep_to' => $cepTo,
            'calculated_distance' => $distance,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function batchSaveDistances(array $distances)
    {
        // Start a transaction to ensure data integrity
        // DB::transaction(function () use ($distances) {
            // Bulk insert distances
            Distance::insert($distances);
        // });
    }
}
