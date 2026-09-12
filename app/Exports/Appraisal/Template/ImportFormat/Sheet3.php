<?php

namespace App\Exports\Appraisal\Template\ImportFormat;


use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet3 implements FromView, WithTitle
{
    private $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    public function view(): View
    {
        return view('exports.excel.appraisal.template.import_format.sheet3', [
            'categories' => $this->categories
        ]);
    }

    public function title(): string
    {
        return 'CATEGORY';
    }
}
