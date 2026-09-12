<?php

namespace App\Exports\Appraisal\Question\ImportFormat;


use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet3 implements FromView, WithTitle
{
    private $templates;

    public function __construct($templates)
    {
        $this->templates = $templates;
    }

    public function view(): View
    {
        return view('exports.excel.appraisal.question.import_format.sheet3', [
            'templates' => $this->templates
        ]);
    }

    public function title(): string
    {
        return 'TEMPLATE';
    }
}
