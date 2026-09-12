<?php

namespace App\Exports\Employee\ImportFormatFamily;


use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet2 implements FromView, WithTitle
{
    private $occupations;

    public function __construct($occupations)
    {
        $this->occupations = $occupations;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format_family.sheet2', [
            'occupations' => $this->occupations
        ]);
    }

    public function title(): string
    {
        return 'OCCUPATION';
    }
}
