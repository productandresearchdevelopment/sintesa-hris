<?php

namespace App\Exports\Employee\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet10 implements FromView, WithTitle
{
    private $religions;

    public function __construct($religions)
    {
        $this->religions = $religions;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format.sheet10', [
            'religions' => $this->religions
        ]);
    }

    public function title(): string
    {
        return 'RELIGION';
    }
}
