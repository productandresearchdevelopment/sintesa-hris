<?php

namespace App\Exports\Employee\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet14 implements FromView, WithTitle
{
    private $provinces;

    public function __construct($provinces)
    {
        $this->provinces = $provinces;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format.sheet14', [
            'provinces' => $this->provinces
        ]);
    }

    public function title(): string
    {
        return 'PROVINCE';
    }
}
