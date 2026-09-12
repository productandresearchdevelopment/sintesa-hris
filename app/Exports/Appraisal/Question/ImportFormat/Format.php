<?php

namespace App\Exports\Appraisal\Question\ImportFormat;

use App\Models\Appraisals\AppraisalQuestionCategory;
use App\Models\Appraisals\AppraisalQuestionTemplate;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Format implements WithMultipleSheets
{

    public function __construct() {}

    public function sheets(): array
    {
        $templates = AppraisalQuestionTemplate::all();
        $categories = AppraisalQuestionCategory::all();

        return [
            'DATA' => new Sheet1($templates, $categories),
            'CATEGORY' => new Sheet2($categories),
            'TEMPLATE' => new Sheet3($templates)
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        info("Sheet {$sheetName} was skipped");
    }
}
