<?php

namespace App\Exports\Users\ImportFormat;


use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet3 implements FromView, WithTitle
{
    private $employees;

    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    public function view(): View
    {
        return view('exports.excel.users.import_format.sheet3', [
            'employees' => $this->employees
        ]);
    }

    public function title(): string
    {
        return 'EMPLOYEES';
    }
}
