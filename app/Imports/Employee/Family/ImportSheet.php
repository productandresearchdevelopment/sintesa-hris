<?php

namespace App\Imports\Employee\Family;

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
            $nik                   = $this->safeGet($rows, $i, 0);
            $name                  = $this->safeGet($rows, $i, 1);
            $birthDate             = $this->safeGet($rows, $i, 2);
            $occupationId          = $this->safeGet($rows, $i, 3);
            $occupationDescription = $this->safeGet($rows, $i, 5);
            $relationId            = $this->safeGet($rows, $i, 6);
            $address               = $this->safeGet($rows, $i, 8);
            $phone                 = $this->safeGet($rows, $i, 9);

            if (empty($nik) && empty($name) && empty($occupationId) && empty($relationId)) {
                continue;
            }

            $totalRow++;

            if (empty($name)) {
                $log[] = ['row' => $i + 1, 'success' => false, 'message' => "Name is required"];
                $totalError++;
                continue;
            }

            if (empty($occupationId)) {
                $log[] = ['row' => $i + 1, 'success' => false, 'message' => "Occupation ID is required"];
                $totalError++;
                continue;
            }

            if (empty($relationId)) {
                $log[] = ['row' => $i + 1, 'success' => false, 'message' => "Relation ID is required"];
                $totalError++;
                continue;
            }

            $occupationRecord = GlobalData::where('group', 'familly_occupation')
                ->where('id', $occupationId)->first();

            if (!$occupationRecord) {
                $log[] = ['row' => $i + 1, 'success' => false, 'message' => "Occupation not found"];
                $totalError++;
                continue;
            }

            $relationRecord = GlobalData::where('group', 'familly')
                ->where('id', $relationId)->first();

            if (!$relationRecord) {
                $log[] = ['row' => $i + 1, 'success' => false, 'message' => "Relation not found"];
                $totalError++;
                continue;
            }

            $parsedData[] = [
                'nik'                    => $nik,
                'name'                   => $name,
                'birth_date'             => $this->parseDate($birthDate),
                'occupation_id'          => is_numeric($occupationId) ? (int) $occupationId : $occupationRecord->id,
                'occupation_description' => $occupationDescription,
                'relation_id'            => is_numeric($relationId) ? (int) $relationId : $relationRecord->id,
                'address'                => $address,
                'phone'                  => $phone,
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
