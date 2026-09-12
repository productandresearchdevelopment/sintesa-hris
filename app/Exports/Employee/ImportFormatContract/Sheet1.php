<?php

namespace App\Exports\Employee\ImportFormatContract;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet1 implements FromView, WithTitle, WithColumnFormatting
{
    private $contract_status;

    public function __construct($contract_status)
    {
        $this->contract_status = $contract_status;
    }

    public function view(): View
    {
        $contract_status = $this->contract_status;

        return view('exports.excel.employee.import_format_contract.sheet1', [
            'contract_status' => $contract_status,
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
