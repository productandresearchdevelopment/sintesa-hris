<?php

namespace App\Exports\Employee\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet7 implements FromView, WithTitle
{
    private $careers;

    public function __construct($careers)
    {
        $this->careers = $careers;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format.sheet7', [
            'careers' => $this->careers
        ]);
    }

    public function title(): string
    {
        return 'CAREER';
    }
}
