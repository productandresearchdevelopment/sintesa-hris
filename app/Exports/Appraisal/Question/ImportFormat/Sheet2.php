<?php

namespace App\Exports\Appraisal\Question\ImportFormat;


use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet2 implements FromView, WithTitle
{
    private $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    public function view(): View
    {
        return view('exports.excel.appraisal.question.import_format.sheet2', [
            'categories' => $this->categories
        ]);
    }

    public function title(): string
    {
        return 'CATEGORY';
    }
}
