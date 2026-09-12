<?php

namespace App\Exports\Employee\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet13 implements FromView, WithTitle
{
    private $cities;

    public function __construct($cities)
    {
        $this->cities = $cities;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format.sheet13', [
            'cities' => $this->cities
        ]);
    }

    public function title(): string
    {
        return 'CITY';
    }
}
