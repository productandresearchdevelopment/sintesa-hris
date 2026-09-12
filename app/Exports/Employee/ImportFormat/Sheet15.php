<?php

namespace App\Exports\Employee\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet15 implements FromView, WithTitle
{
    private $offices;

    public function __construct($offices)
    {
        $this->offices = $offices;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format.sheet15', [
            'offices' => $this->offices
        ]);
    }

    public function title(): string
    {
        return 'OFFICE';
    }
}
