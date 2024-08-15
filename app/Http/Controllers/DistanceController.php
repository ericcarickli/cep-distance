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

        foreach ($data as $row) {
            list($cepFrom, $cepTo) = $row;

            try {
                $distance = $this->distanceService->calculateDistance($cepFrom, $cepTo);
                $savedDistance = $this->distanceService->saveDistance($cepFrom, $cepTo, $distance);
                $savedDistances[] = $savedDistance;
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }

        return response()->json($savedDistances);
    }
}
