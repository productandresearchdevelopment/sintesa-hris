<?php

namespace App\Exports\Employee\ImportFormatEducation;


use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet2 implements FromView, WithTitle
{
    private $educations;

    public function __construct($educations)
    {
        $this->educations = $educations;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format_education.sheet2', [
            'educations' => $this->educations
        ]);
    }

    public function title(): string
    {
        return 'EDUCATION';
    }
}
