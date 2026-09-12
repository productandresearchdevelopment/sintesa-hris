<?php

namespace App\Exports\Employee\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet8 implements FromView, WithTitle
{
    private $genders;

    public function __construct($genders)
    {
        $this->genders = $genders;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format.sheet8', [
            'genders' => $this->genders
        ]);
    }

    public function title(): string
    {
        return 'GENDER';
    }
}
