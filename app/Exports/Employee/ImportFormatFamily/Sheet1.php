<?php

namespace App\Exports\Employee\ImportFormatFamily;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet1 implements FromView, WithTitle, WithColumnFormatting
{
    private $occupations;
    private $relations;

    public function __construct($occupations, $relations)
    {
        $this->occupations = $occupations;
        $this->relations = $relations;
    }

    public function view(): View
    {

        return view('exports.excel.employee.import_format_family.sheet1', [
            'occupations' => $this->occupations,
            'relations' => $this->relations
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
