<?php

namespace App\Imports\Employee\Training;

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
            $title            = $this->safeGet($rows, $i, 0);
            $location         = $this->safeGet($rows, $i, 1);
            $startDate        = $this->safeGet($rows, $i, 2);
            $endDate          = $this->safeGet($rows, $i, 3);
            $description      = $this->safeGet($rows, $i, 4);
            $isInternal       = $this->parseBoolean($this->safeGet($rows, $i, 5));
            $isCertification  = $this->parseBoolean($this->safeGet($rows, $i, 6));

            if (empty($title)) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Training Title is required"
                ];
                $totalError++;
                continue;
            }

            $parsedData[] = [
                'title'             => $title,
                'location'          => $location,
                'start_date'        => $this->parseDate($startDate),
                'end_date'          => $this->parseDate($endDate),
                'description'       => $description,
                'is_internal'       => $isInternal,
                'is_certification'  => $isCertification,
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

    private function parseBoolean($value): ?int
    {
        if (!$value) return null;

        $val = strtolower(trim($value));

        if (in_array($val, ['yes', 'y', 'ya', 'true', '1'])) {
            return 1;
        }

        if (in_array($val, ['no', 'n', 'tidak', 'false', '0'])) {
            return 0;
        }

        return null;
    }

    private function safeGet($rows, $i, $index)
    {
        return isset($rows[$i][$index]) ? trim($rows[$i][$index]) : null;
    }
}
