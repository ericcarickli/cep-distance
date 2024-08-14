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
            return response()->json(['distance' => $distance]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
