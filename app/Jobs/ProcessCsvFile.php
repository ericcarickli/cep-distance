<?php

namespace App\Jobs;

use App\Services\DistanceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        Log::info('Job started for file: ' . $this->filePath);

        if (!file_exists(storage_path('app/' . $this->filePath))) {
            Log::error('File does not exist at path: ' . $this->filePath);
            return;
        }

        $filePath = storage_path('app/' . $this->filePath);

        $chunkSize = 1000;
        $csv = new SplFileObject($filePath);
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

            Log::info('Processing chunk of ' . count($rows) . ' rows.');

            foreach ($rows as $row) {
                list($cepFrom, $cepTo) = $row;
                try {
                    $distance = $distanceService->calculateDistance($cepFrom, $cepTo);
                    $distanceData = [
                        'cep_from' => $cepFrom,
                        'cep_to' => $cepTo,
                        'calculated_distance' => $distance,
                        'created_at' => now()->toIso8601String(),
                        'updated_at' => now()->toIso8601String(),
                    ];

                    $distancesProcessed[] = $distanceData;
                    if (count($distancesProcessed) >= 100) {
                        $distanceService->batchSaveDistances($distancesProcessed);
                        $savedDistances = array_merge($savedDistances, $distancesProcessed);
                        $distancesProcessed = [];
                    }
                } catch (\Exception $e) {
                    Log::error('Error processing row (' . $cepFrom . ', ' . $cepTo . '): ' . $e->getMessage());
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

        Log::info('Job completed. Total rows processed: ' . $rowCount);
    }
}
