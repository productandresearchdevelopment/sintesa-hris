<?php

namespace App\Imports\Employee\Citizen;

use App\Models\GlobalData;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ImportSheet implements ToCollection, WithChunkReading
{
    public $logs = [];
    public $data = [];
    private $user = null;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function collection(Collection $rows)
    {
        $startLine = 5;
        $totalRow = 0;
        $totalSuccess = 0;
        $totalError = 0;
        $log = [];
        $parsedData = [];

        for ($i = $startLine; $i < count($rows); $i++) {
            $citizenId   = $this->safeGet($rows, $i, 0);
            $value       = $this->safeGet($rows, $i, 2);
            $description = $this->safeGet($rows, $i, 3);

            if (empty($citizenId) && empty($value) && empty($description)) {
                continue;
            }

            $totalRow++;

            if (empty($citizenId)) {
                $log[] = [
                    'row'     => $i + 1,
                    'success' => false,
                    'message' => "Citizen ID is required"
                ];
                $totalError++;
                continue;
            }

            $citizenRecord = GlobalData::where('group', 'citizen')
                ->where('id', $citizenId)
                ->first();

            if (!$citizenRecord) {
                $log[] = [
                    'row'     => $i + 1,
                    'success' => false,
                    'message' => "Citizen ID not found: $citizenId"
                ];
                $totalError++;
                continue;
            }

            $parsedData[] = [
                'citizen_id'  => is_numeric($citizenId) ? (int) $citizenId : $citizenId,
                'value'       => $value,
                'description' => $description,
            ];

            $totalSuccess++;
        }

        $this->data = $parsedData;
        $this->logs = [
            'totalRow'     => $totalRow,
            'totalSuccess' => $totalSuccess,
            'totalError'   => $totalError,
            'errorLog'     => $log,
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }

    private function safeGet($rows, $i, $index)
    {
        return isset($rows[$i][$index]) ? trim($rows[$i][$index]) : null;
    }
}
