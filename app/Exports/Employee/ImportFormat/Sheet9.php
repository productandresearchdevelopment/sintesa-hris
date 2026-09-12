<?php

namespace App\Exports\Employee\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet9 implements FromView, WithTitle
{
    private $maritals;

    public function __construct($maritals)
    {
        $this->maritals = $maritals;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format.sheet9', [
            'maritals' => $this->maritals
        ]);
    }

    public function title(): string
    {
        return 'MARITAL';
    }
}
