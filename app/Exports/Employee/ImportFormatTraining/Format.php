<?php

namespace App\Exports\Employee\ImportFormatTraining;

use App\Models\GlobalData;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Format implements WithMultipleSheets
{

    public function __construct() {}

    public function sheets(): array
    {
        return [
            'DATA' => new Sheet1()
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        info("Sheet {$sheetName} was skipped");
    }
}
