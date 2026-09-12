<?php

namespace App\Exports\Employee\ImportFormatCitizen;

use App\Models\GlobalData;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Format implements WithMultipleSheets
{

    public function __construct() {}

    public function sheets(): array
    {
        $citizens = GlobalData::where('group', 'citizen')->get();

        return [
            'DATA' => new Sheet1($citizens),
            'CITIZEN' => new Sheet2($citizens),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        info("Sheet {$sheetName} was skipped");
    }
}
