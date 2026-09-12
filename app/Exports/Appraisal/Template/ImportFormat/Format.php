<?php

namespace App\Exports\Appraisal\Template\ImportFormat;

use App\Models\Appraisals\AppraisalQuestionCategory;
use App\Models\Division;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Format implements WithMultipleSheets
{

    public function __construct() {}

    public function sheets(): array
    {
        $divisions = Division::all();
        $categories = AppraisalQuestionCategory::all();

        return [
            'DATA' => new Sheet1($divisions, $categories),
            'DIVISION' => new Sheet2($divisions),
            'CATEGORY' => new Sheet3($categories)
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        info("Sheet {$sheetName} was skipped");
    }
}
