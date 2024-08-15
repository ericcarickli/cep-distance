<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DistanceService;

class DistanceController extends Controller
{
    protected $distanceService;

    public function __construct(DistanceService $distanceService)
    {
        $this->distanceService = $distanceService;
    }

    public function listDistances() {
        $distances = $this->distanceService->getAllDistances();
        return response()->json($distances);
    }

    public function calculate(Request $request)
    {
        $cepFrom = $request->input('cep_from');
        $cepTo = $request->input('cep_to');

        try {
            $distance = $this->distanceService->calculateDistance($cepFrom, $cepTo);
            $this->distanceService->saveDistance($cepFrom, $cepTo, $distance);

            return response()->json(['distance' => $distance]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function calculateMass(Request $request) {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        
        $data = array_map('str_getcsv', file($path));
        array_shift($data);

        $savedDistances = [];

        foreach ($data as $index => $row) {
            list($cepFrom, $cepTo) = $row;

            try {
                $distance = $this->distanceService->calculateDistance($cepFrom, $cepTo);
                // $savedDistance = $this->distanceService->saveDistance($cepFrom, $cepTo, $distance);
                
                $distanceData = [
                    'cep_from' => $cepFrom,
                    'cep_to' => $cepTo,
                    'calculated_distance' => $distance,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $savedDistances[] = $distanceData;
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'csvRow' => $index + 2 // Adding 2 to account for header row and 0-based index
                ], 400);
            }
        }

        // var_dump($savedDistances);
        return response()->json($savedDistances);
    }
}
