<?php

namespace App\Exports\Appraisal\Template\ImportFormat;


use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet2 implements FromView, WithTitle
{
    private $divisions;

    public function __construct($divisions)
    {
        $this->divisions = $divisions;
    }

    public function view(): View
    {
        return view('exports.excel.appraisal.template.import_format.sheet2', [
            'divisions' => $this->divisions
        ]);
    }

    public function title(): string
    {
        return 'DIVISION';
    }
}
