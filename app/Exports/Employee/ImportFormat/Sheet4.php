<?php

namespace App\Exports\Employee\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet4 implements FromView, WithTitle
{
    private $divisions;

    public function __construct($divisions)
    {
        $this->divisions = $divisions;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format.sheet4', [
            'divisions' => $this->divisions
        ]);
    }

    public function title(): string
    {
        return 'DIVISION';
    }
}
