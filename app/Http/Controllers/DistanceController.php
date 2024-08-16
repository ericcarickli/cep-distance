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
    
        $chunkSize = 1000; // Number of rows per chunk
        $csv = new \SplFileObject($path);
        $csv->setFlags(\SplFileObject::READ_CSV);
        $csv->seek(1); // Skip header row
    
        $savedDistances = [];
        $rowCount = 0;
    
        while (!$csv->eof()) {
            $rows = [];
            for ($i = 0; $i < $chunkSize; $i++) {
                if ($csv->eof()) break;
                $rows[] = $csv->fgetcsv();
            }
    
            foreach ($rows as $row) {
                list($cepFrom, $cepTo) = $row;
                try {
                    $distance = $this->distanceService->calculateDistance($cepFrom, $cepTo);
                    $distanceData = [
                        'cep_from' => $cepFrom,
                        'cep_to' => $cepTo,
                        'calculated_distance' => $distance,
                        'created_at' => now()->toIso8601String(),
                        'updated_at' => now()->toIso8601String(),
                    ];
    
                    $distancesProcessed[] = $distanceData;
                    if (count($distancesProcessed) >= 100) {
                        $this->distanceService->batchSaveDistances($distancesProcessed);
                        $savedDistances = array_merge($savedDistances, $distancesProcessed);
                        $distancesProcessed = [];
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'error' => $e->getMessage(),
                        'csv_row' => $rowCount + 2 // Adding 2 to account for header row and 0-based index
                    ], 400);
                }
                $rowCount++;
            }
        }

        // Save any remaining distances and append to the list of saved distances
        if (!empty($distancesProcessed)) {
            $this->distanceService->batchSaveDistances($distancesProcessed);
            $savedDistances = array_merge($savedDistances, $distancesProcessed);
        }
    
        return response()->json([
            'processed_rows' => $rowCount,
            'saved_distances' => $savedDistances
        ]);
    }
}
