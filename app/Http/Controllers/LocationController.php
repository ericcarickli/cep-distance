<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    public function validateCep(Request $request)
    {
        $cep = $request->input('cep');
        $response = Http::get("https://brasilapi.com.br/api/cep/v1/{$cep}");

        if ($response->successful()) {
            $data = $response->json();
            // Return the data or perform further processing
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Invalid CEP'], 400);
        }
    }

    public function calculateDistance(Request $request)
    {
        $lat1 = $request->input('lat1');
        $lon1 = $request->input('lon1');
        $lat2 = $request->input('lat2');
        $lon2 = $request->input('lon2');

        $distance = $this->haversineGreatCircleDistance($lat1, $lon1, $lat2, $lon2);

        CEPDistance::create([
            'cep_from' => $request->input('cep_from'),
            'cep_to' => $request->input('cep_to'),
            'calculated_distance' => $distance,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['distance' => $distance]);
    }

    private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
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
