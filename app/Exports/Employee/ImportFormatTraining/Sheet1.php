<?php

namespace App\Exports\Employee\ImportFormatTraining;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet1 implements FromView, WithTitle, WithColumnFormatting
{
    public function __construct() {}

    public function view(): View
    {

        return view('exports.excel.employee.import_format_training.sheet1', []);
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
