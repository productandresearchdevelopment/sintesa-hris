<?php

namespace App\Exports\Employee\ImportFormatCareer;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet1 implements FromView, WithTitle, WithColumnFormatting
{
    private $careers;
    private $placements;
    private $organizations;

    public function __construct($careers, $placements, $organizations)
    {
        $this->careers = $careers;
        $this->placements = $placements;
        $this->organizations = $organizations;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format_career.sheet1', [
            'careers' => $this->careers,
            'placements' => $this->placements,
            'organizations' => $this->organizations
        ]);
    }

    public function columnFormats(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'DATA';
    }
}
