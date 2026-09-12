<?php

namespace App\Exports\Employee\ImportFormatCareer;

use App\Models\GlobalData;
use App\Models\Organization;
use App\Models\Placement;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Format implements WithMultipleSheets
{

    public function __construct() {}

    public function sheets(): array
    {
        $careers = GlobalData::where('group', 'career')->get();
        $placements = Placement::all();
        $organizations = Organization::all();

        return [
            'DATA' => new Sheet1($careers, $placements, $organizations),
            'CAREER' => new Sheet2($careers),
            'PLACEMENT' => new Sheet3($placements),
            'ORGANIZATION' => new Sheet4($organizations),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        info("Sheet {$sheetName} was skipped");
    }
}
