<?php

namespace App\Exports\Users\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet1 implements FromView, WithTitle, WithColumnFormatting
{
    private $roles;
    private $employees;
    private $organizations;

    public function __construct($roles, $employees, $organizations)
    {
        $this->roles = $roles;
        $this->employees  = $employees;
        $this->organizations = $organizations;
    }

    public function view(): View
    {
        $roles = $this->roles;
        $employees = $this->employees;
        $organizations = $this->organizations;

        return view('exports.excel.users.import_format.sheet1', [
            'roles' => $roles,
            'employees' => $employees,
            'organizations' => $organizations
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
