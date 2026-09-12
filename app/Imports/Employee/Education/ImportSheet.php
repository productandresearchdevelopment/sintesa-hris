<?php

namespace App\Imports\Employee\Education;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Models\GlobalData;

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
            $educationId      = $this->safeGet($rows, $i, 0);
            $majorId          = $this->safeGet($rows, $i, 2);
            $institution      = $this->safeGet($rows, $i, 4);
            $graduationYear   = $this->safeGet($rows, $i, 5);
            $ipk              = $this->safeGet($rows, $i, 6);
            $description      = $this->safeGet($rows, $i, 7);

            if (empty($educationId) && empty($majorId) && empty($institution)) {
                continue;
            }

            $totalRow++;

            if (empty($educationId)) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Education ID is required"
                ];
                $totalError++;
                continue;
            }

            if (empty($majorId)) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Major ID is required"
                ];
                $totalError++;
                continue;
            }

            if (empty($institution)) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Institution is required"
                ];
                $totalError++;
                continue;
            }

            $educationRecord = GlobalData::where('group', 'education')
                ->where('id', $educationId)
                ->first();

            if (!$educationRecord) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Education not found"
                ];
                $totalError++;
                continue;
            }

            $majorRecord = GlobalData::where('group', 'education_major')
                ->where('id', $majorId)
                ->first();

            if (!$majorRecord) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Major not found"
                ];
                $totalError++;
                continue;
            }

            $parsedData[] = [
                'education_id'   => is_numeric($educationId) ? (int) $educationId : $educationRecord->id,
                'major_id'       => is_numeric($majorId) ? (int) $majorId : $majorRecord->id,
                'institution'    => $institution,
                'graduate' => $graduationYear,
                'ipk'            => $ipk,
                'description'    => $description,
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
