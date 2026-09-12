<?php

namespace App\Exports\Appraisal\Template\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Sheet1 implements FromView, WithTitle, WithColumnFormatting
{
    private $divisions;
    private $categories;


    public function __construct($divisions, $categories)
    {
        $this->divisions = $divisions;
        $this->categories = $categories;
    }

    public function view(): View
    {
        $divisions = $this->divisions;
        $categories = $this->categories;

        return view('exports.excel.appraisal.template.import_format.sheet1', [
            'divisions' => $divisions,
            'categories' => $categories
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
