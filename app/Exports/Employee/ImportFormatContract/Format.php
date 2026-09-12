<?php

namespace App\Exports\Employee\ImportFormatContract;

use App\Models\GlobalData;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Format implements WithMultipleSheets
{

    public function __construct() {}

    public function sheets(): array
    {
        $contract_status = GlobalData::where('group', 'contract_status')->get();

        return [
            'DATA' => new Sheet1($contract_status),
            'CONTRACT_STATUS' => new Sheet2($contract_status)
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        info("Sheet {$sheetName} was skipped");
    }
}
