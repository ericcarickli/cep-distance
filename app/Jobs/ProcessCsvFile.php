<?php

namespace App\Jobs;

use App\Services\DistanceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SplFileObject;

class ProcessCsvFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle(DistanceService $distanceService)
    {
        $chunkSize = 1000;
        $csv = new SplFileObject($this->filePath);
        $csv->setFlags(SplFileObject::READ_CSV);
        $csv->seek(1);

        $savedDistances = [];
        $distancesProcessed = [];
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
                    $distance = $distanceService->calculateDistance($cepFrom, $cepTo);
                    $distanceData = [
                        'cep_from' => $cepFrom,
                        'cep_to' => $cepTo,
                        'calculated_distance' => $distance,
                    ];

                    $distancesProcessed[] = $distanceData;
                    if (count($distancesProcessed) >= 100) {
                        $distanceService->batchSaveDistances($distancesProcessed);
                        $savedDistances = array_merge($savedDistances, $distancesProcessed);
                        $distancesProcessed = [];
                    }
                } catch (\Exception $e) {
                    // Handle exception
                }
                $rowCount++;
            }

            if (!empty($distancesProcessed)) {
                $distanceService->batchSaveDistances($distancesProcessed);
                $savedDistances = array_merge($savedDistances, $distancesProcessed);
                $distancesProcessed = [];
            }
        }

        // Save any remaining distances
        if (!empty($distancesProcessed)) {
            $distanceService->batchSaveDistances($distancesProcessed);
            $savedDistances = array_merge($savedDistances, $distancesProcessed);
        }
    }
}
