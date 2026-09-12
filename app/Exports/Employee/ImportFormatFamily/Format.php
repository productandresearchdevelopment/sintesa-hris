<?php

namespace App\Exports\Employee\ImportFormatFamily;

use App\Models\GlobalData;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Format implements WithMultipleSheets
{

    public function __construct() {}

    public function sheets(): array
    {
        $occupations = GlobalData::where('group', 'familly_occupation')->get();
        $relations = GlobalData::where('group', 'familly')->get();

        return [
            'DATA' => new Sheet1($occupations, $relations),
            'OCCUPATION' => new Sheet2($occupations),
            'RELATION' => new Sheet3($relations),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        info("Sheet {$sheetName} was skipped");
    }
}
