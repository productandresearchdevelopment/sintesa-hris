<?php

namespace App\Exports\Users\ImportFormat;


use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet2 implements FromView, WithTitle
{
    private $roles;

    public function __construct($roles)
    {
        $this->roles = $roles;
    }

    public function view(): View
    {
        return view('exports.excel.users.import_format.sheet2', [
            'roles' => $this->roles
        ]);
    }

    public function title(): string
    {
        return 'ROLES';
    }
}
