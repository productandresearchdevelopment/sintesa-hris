<?php

namespace App\Imports\Employee\Career;

use App\Models\GlobalData;
use App\Models\Organization;
use App\Models\Placement;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Shared\Date;

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

            $careerId        = $this->safeGet($rows, $i, 0);
            $placementId     = $this->safeGet($rows, $i, 2);
            $organizationId  = $this->safeGet($rows, $i, 4);
            $date            = $this->safeGet($rows, $i, 6);
            $description     = $this->safeGet($rows, $i, 7);

            if (empty($careerId) && empty($placementId) && empty($organizationId) && empty($date)) {
                continue;
            }

            $totalRow++;

            if (empty($careerId)) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Career ID is required"
                ];
                $totalError++;
                continue;
            }

            if (empty($placementId)) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Placement ID is required"
                ];
                $totalError++;
                continue;
            }

            if (empty($organizationId)) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Organization ID is required"
                ];
                $totalError++;
                continue;
            }

            if (empty($date)) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Date is required"
                ];
                $totalError++;
                continue;
            }

            $careerRecord = GlobalData::where('group', 'career')
                ->where('id', $careerId)
                ->first();

            $placementRecord = Placement::where('id', $placementId)
                ->first();

            $organizationRecord = Organization::where('id', $organizationId)
                ->first();

            if (!$careerRecord) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Career ID not found: $careerId"
                ];
                $totalError++;
                continue;
            }

            if (!$placementRecord) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Placement ID not found: $placementId"
                ];
                $totalError++;
                continue;
            }

            if (!$organizationRecord) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Organization ID not found: $organizationId"
                ];
                $totalError++;
                continue;
            }

            $parsedData[] = [
                'career_id'       => is_numeric($careerId) ? (int) $careerId : $careerRecord->id,
                'placement_id'  => is_numeric($placementId) ? (int) $placementId : $placementRecord->id,
                'org_id' => is_numeric($organizationId) ? (int) $organizationId : $organizationRecord->id,
                'date'    => $this->parseDate($date),
                'description'   => $description,
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

    private function parseDate($value): ?string
    {
        if (!$value) return null;

        try {
            if (is_numeric($value)) {
                return Carbon::instance(Date::excelToDateTimeObject($value))->format('m/d/Y');
            }

            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) {
                return Carbon::createFromFormat('m/d/Y', $value)->format('m/d/Y');
            }

            return Carbon::parse($value)->format('m/d/Y');
        } catch (\Exception $e) {
            return null;
        }
    }



    private function safeGet($rows, $i, $index)
    {
        return isset($rows[$i][$index]) ? trim($rows[$i][$index]) : null;
    }
}
