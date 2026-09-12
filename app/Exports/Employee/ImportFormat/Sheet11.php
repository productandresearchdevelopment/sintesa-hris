<?php

namespace App\Exports\Employee\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet11 implements FromView, WithTitle
{
    private $banks;

    public function __construct($banks)
    {
        $this->banks = $banks;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format.sheet11', [
            'banks' => $this->banks
        ]);
    }

    public function title(): string
    {
        return 'BANK';
    }
}
