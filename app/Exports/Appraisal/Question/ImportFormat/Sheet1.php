<?php

namespace App\Exports\Appraisal\Question\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Sheet1 implements FromView, WithTitle, WithColumnFormatting
{
    private $templates;
    private $categories;


    public function __construct($templates, $categories)
    {
        $this->templates = $templates;
        $this->categories = $categories;
    }

    public function view(): View
    {
        $templates = $this->templates;
        $categories = $this->categories;

        return view('exports.excel.appraisal.question.import_format.sheet1', [
            'templates' => $templates,
            'categories' => $categories,
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function title(): string
    {
        return 'DATA';
    }
}
