<?php

namespace App\Exports\Employee\ImportFormat;


use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet2 implements FromView, WithTitle
{
    private $companies;

    public function __construct($companies)
    {
        $this->companies = $companies;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format.sheet2', [
            'companies' => $this->companies
        ]);
    }

    public function title(): string
    {
        return 'COMPANY';
    }
}
