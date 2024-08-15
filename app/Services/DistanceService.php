<?php

namespace App\Services;

use App\Repositories\DistanceRepository;
use Illuminate\Support\Facades\Http;

class DistanceService
{
    protected $distanceRepository;

    public function __construct(DistanceRepository $distanceRepository)
    {
        $this->distanceRepository = $distanceRepository;
    }

    public function getAllDistances() {
        return $this->distanceRepository->getAllDistances();
    }

    public function calculateDistance(string $cepFrom, string $cepTo)
    {
        $cepFromValidated = $this->validateCep($cepFrom);
        $cepToValidated = $this->validateCep($cepTo);

        if (isset($cepFromValidated['error'])) {
            throw new \Exception('from_cep_invalid', 400);
        }

        //CEP de origem inválido  => from_cep_invalid
        // CEP de destino inválido  => to_cep_invalid
        // Coordenadas do CEP de origem não estão disponíveis na Brasil API => from_cep_coordinates_not_available
        // Coordenadas do CEP de destino não estão disponíveis na Brasil API => to_cep_coordinates_not_available

        if (isset($cepToValidated['error'])) {
            throw new \Exception('to_cep_invalid', 400);
        }

        if (!isset($cepFromValidated['location']['coordinates']['latitude']) ||
            !isset($cepFromValidated['location']['coordinates']['longitude'])) {
            throw new \Exception('from_cep_coordinates_not_available', 400);
        }

        if (!isset($cepToValidated['location']['coordinates']['latitude']) ||
            !isset($cepToValidated['location']['coordinates']['longitude'])) {
            throw new \Exception('to_cep_coordinates_not_available', 400);
        }

        $latFrom = $cepFromValidated['location']['coordinates']['latitude'];
        $lonFrom = $cepFromValidated['location']['coordinates']['longitude'];
        $latTo = $cepToValidated['location']['coordinates']['latitude'];
        $lonTo = $cepToValidated['location']['coordinates']['longitude'];

        if ($latFrom === $latTo && $lonFrom === $lonTo) {
            return 0;
        }

        // Haversine formula
        $distance = $this->haversineGreatCircleDistance($latFrom, $lonFrom, $latTo, $lonTo);

        $this->distanceRepository->saveDistance($cepFrom, $cepTo, $distance);

        return $distance;
    }

    private function validateCep(string $cep)
    {
        $response = Http::get("https://brasilapi.com.br/api/cep/v2/{$cep}");

        if ($response->successful()) {
            return $response->json();
        } else {
            return ['error' => 'Invalid CEP'];
        }
    }

    private function haversineGreatCircleDistance(float $latitudeFrom, float $longitudeFrom, float $latitudeTo, float $longitudeTo, int $earthRadius = 6371)
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
