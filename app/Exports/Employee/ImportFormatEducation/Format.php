<?php

namespace App\Exports\Employee\ImportFormatEducation;

use App\Models\GlobalData;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Format implements WithMultipleSheets
{

    public function __construct() {}

    public function sheets(): array
    {
        $educations = GlobalData::where('group', 'education')->get();
        $majors = GlobalData::where('group', 'education_major')->get();

        return [
            'DATA' => new Sheet1($educations, $majors),
            'EDUCATION' => new Sheet2($educations),
            'MAJOR' => new Sheet3($majors),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        info("Sheet {$sheetName} was skipped");
    }
}
