<?php

namespace App\Exports\Employee\ImportFormatEducation;


use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet3 implements FromView, WithTitle
{
    private $majors;

    public function __construct($majors)
    {
        $this->majors = $majors;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format_education.sheet3', [
            'majors' => $this->majors
        ]);
    }

    public function title(): string
    {
        return 'MAJOR';
    }
}
