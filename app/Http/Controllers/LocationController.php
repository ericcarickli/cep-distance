<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\CEPDistance;

class LocationController extends Controller
{
    public function listDistances()
    {
        $distances = CEPDistance::all();

        return response()->json($distances);
    }

    public function calculate(Request $request)
    {
        $cepFromValidated = $this->validateCep($request->input('cep_from'));
        $cepToValidated = $this->validateCep($request->input('cep_to'));

        if (isset($cepFromValidated['error'])) {
            return response()->json(['error' => 'Invalid CEP from'], 400);
        }

        if (isset($cepToValidated['error'])) {
            return response()->json(['error' => 'Invalid CEP to'], 400);
        }

        $latFrom = $cepFromValidated['location']['coordinates']['latitude'];
        $lonFrom = $cepFromValidated['location']['coordinates']['longitude'];
        $latTo = $cepToValidated['location']['coordinates']['latitude'];
        $lonTo = $cepToValidated['location']['coordinates']['longitude'];

        // Haversine formula
        $distance = $this->haversineGreatCircleDistance($latFrom, $lonFrom, $latTo, $lonTo);

        CEPDistance::create([
            'cep_from' => $request->input('cep_from'),
            'cep_to' => $request->input('cep_to'),
            'calculated_distance' => $distance,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['distance' => $distance]);
    }

    private function validateCep(String $cep)
    {
        $response = Http::get("https://brasilapi.com.br/api/cep/v2/{$cep}");

        if ($response->successful()) {
            return $response->json();
        } else {
            return ['error' => 'Invalid CEP'];
        }
    }

    private function haversineGreatCircleDistance(String $latitudeFrom, String $longitudeFrom, String $latitudeTo, String $longitudeTo, Int $earthRadius = 6371)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}
