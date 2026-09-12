<?php

namespace App\Exports\Employee\ImportFormatCitizen;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet1 implements FromView, WithTitle, WithColumnFormatting
{
    private $citizens;

    public function __construct($citizens)
    {
        $this->citizens = $citizens;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format_citizen.sheet1', [
            'citizens' => $this->citizens,
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
