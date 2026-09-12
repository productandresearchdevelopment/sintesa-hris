<?php

namespace App\Exports\Employee\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet5 implements FromView, WithTitle
{
    private $placements;

    public function __construct($placements)
    {
        $this->placements = $placements;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format.sheet5', [
            'placements' => $this->placements
        ]);
    }

    public function title(): string
    {
        return 'PLACEMENT';
    }
}
