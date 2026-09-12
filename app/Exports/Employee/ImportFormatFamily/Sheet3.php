<?php

namespace App\Exports\Employee\ImportFormatFamily;


use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet3 implements FromView, WithTitle
{
    private $relations;

    public function __construct($relations)
    {
        $this->relations = $relations;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format_family.sheet3', [
            'relations' => $this->relations
        ]);
    }

    public function title(): string
    {
        return 'RELATION';
    }
}
