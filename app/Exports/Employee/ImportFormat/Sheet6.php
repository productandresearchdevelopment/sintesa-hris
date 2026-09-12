<?php

namespace App\Exports\Employee\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet6 implements FromView, WithTitle
{
    private $contracts;

    public function __construct($contracts)
    {
        $this->contracts = $contracts;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format.sheet6', [
            'contracts' => $this->contracts
        ]);
    }

    public function title(): string
    {
        return 'CONTRACT';
    }
}
