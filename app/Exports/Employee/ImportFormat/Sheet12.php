<?php

namespace App\Exports\Employee\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet12 implements FromView, WithTitle
{
    private $emergency_relations;

    public function __construct($emergency_relations)
    {
        $this->emergency_relations = $emergency_relations;
    }

    public function view(): View
    {
        return view('exports.excel.employee.import_format.sheet12', [
            'emergency_relations' => $this->emergency_relations
        ]);
    }

    public function title(): string
    {
        return 'EMERGENCY';
    }
}
