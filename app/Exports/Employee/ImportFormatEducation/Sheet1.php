<?php

namespace App\Exports\Employee\ImportFormatEducation;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet1 implements FromView, WithTitle, WithColumnFormatting
{
    private $educations;
    private $majors;

    public function __construct($educations, $majors)
    {
        $this->educations = $educations;
        $this->majors = $majors;
    }

    public function view(): View
    {

        return view('exports.excel.employee.import_format_education.sheet1', [
            'educations' => $this->educations,
            'majors' => $this->majors
        ]);
    }

    public function columnFormats(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'DATA';
    }
}
