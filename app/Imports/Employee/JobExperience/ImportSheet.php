<?php

namespace App\Imports\Employee\JobExperience;

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

            $name            = $this->safeGet($rows, $i, 0);
            $startDate       = $this->safeGet($rows, $i, 1);
            $endDate         = $this->safeGet($rows, $i, 2);
            $jobTitle        = $this->safeGet($rows, $i, 3);
            $jobDescription  = $this->safeGet($rows, $i, 4);
            $salary          = $this->safeGet($rows, $i, 5);
            $reasonLeaving   = $this->safeGet($rows, $i, 6);
            $description     = $this->safeGet($rows, $i, 7);

            if (empty($name) && empty($startDate) && empty($jobTitle)) {
                continue;
            }

            $totalRow++;

            if (empty($name)) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Job Name is required"
                ];
                $totalError++;
                continue;
            }

            if (empty($startDate)) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Start Date is required"
                ];
                $totalError++;
                continue;
            }

            if (empty($jobTitle)) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Job Title is required"
                ];
                $totalError++;
                continue;
            }

            $parsedData[] = [
                'name'             => $name,
                'start_date'       => $this->parseDate($startDate),
                'end_date'         => $this->parseDate($endDate),
                'job_title'        => $jobTitle,
                'job_description'  => $jobDescription,
                'salary'           => $salary,
                'reason_leaving'   => $reasonLeaving,
                'description'      => $description,
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
            $value = trim($value);

            if (is_numeric($value) && strlen($value) == 4) {
                return $value . '-01-01';
            }

            if (is_numeric($value)) {
                return Carbon::instance(
                    Date::excelToDateTimeObject($value)
                )->format('Y-m-d');
            }

            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) {
                return Carbon::createFromFormat('m/d/Y', $value)->format('Y-m-d');
            }

            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function safeGet($rows, $i, $index)
    {
        return isset($rows[$i][$index]) ? trim($rows[$i][$index]) : null;
    }
}
