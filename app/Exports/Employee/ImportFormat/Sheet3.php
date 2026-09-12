<?php

namespace App\Exports\Employee\ImportFormat;


use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet3 implements FromView, WithTitle
{
    private $organizations;

    public function __construct($organizations)
    {
        $this->organizations = $organizations;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format.sheet3', [
            'organizations' => $this->organizations
        ]);
    }

    public function title(): string
    {
        return 'ORGANIZATION';
    }
}
