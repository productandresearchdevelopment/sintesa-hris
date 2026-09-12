<?php

namespace App\Exports\Users\ImportFormat;


use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet4 implements FromView, WithTitle
{
    private $organizations;

    public function __construct($organizations)
    {
        $this->organizations = $organizations;
    }

    public function view(): View
    {
        return view('exports.excel.users.import_format.sheet4', [
            'organizations' => $this->organizations
        ]);
    }

    public function title(): string
    {
        return 'ORGANIZATIONS';
    }
}
