<?php

namespace App\Exports\Employee\ImportFormatCareer;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet3 implements FromView, WithTitle
{
    private $placements;

    public function __construct($placements)
    {
        $this->placements = $placements;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format_career.sheet3', [
            'placements' => $this->placements
        ]);
    }

    public function title(): string
    {
        return 'PLACEMENT';
    }
}
