<?php

namespace App\Imports\Employee\Contract;

use App\Models\GlobalData;
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
            $termId      = $this->safeGet($rows, $i, 0);
            $startDate   = $this->safeGet($rows, $i, 2);
            $endDate     = $this->safeGet($rows, $i, 3);
            $description = $this->safeGet($rows, $i, 4);

            if (empty($termId) && empty($startDate)) {
                continue;
            }

            $totalRow++;

            if (empty($termId)) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Term ID is required"
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

            $termRecord = GlobalData::where('group', 'contract_status')
                ->where('id', $termId)
                ->first();

            if (!$termRecord) {
                $log[] = [
                    'row' => $i + 1,
                    'success' => false,
                    'message' => "Term ID not found: $termId"
                ];
                $totalError++;
                continue;
            }

            $parsedData[] = [
                'term_id'     => is_numeric($termId) ? (int) $termId : $termRecord->id,
                'status_id'   => is_numeric($termRecord->id) ? (int) $termRecord->id : $termRecord->id,
                'start_date'  => $this->parseDate($startDate),
                'end_date'    => $this->parseDate($endDate),
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
