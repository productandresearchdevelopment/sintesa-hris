<?php

namespace App\Exports\Users\ImportFormat;

use App\Models\Employees\Employee;
use App\Models\Organization;
use App\SystemModels\Auth\Role;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Format implements WithMultipleSheets
{
    protected Authenticatable $user;

    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
    }

    public function sheets(): array
    {
        $rolesQuery = Role::query();

        if (strtolower($this->user->role->name) !== 'developer') {
            $rolesQuery->whereRaw('LOWER(name) != ?', ['developer']);
        }

        $roles         = $rolesQuery->get();
        $organizations = Organization::all();
        $employees     = Employee::all();

        return [
            // 'DATA' => new SheetMain($roles),
            // 'ROLE' => new SheetData('ROLE', $roles),
            'DATA'          => new Sheet1($roles, $employees, $organizations),
            'ROLES'         => new Sheet2($roles),
            'EMPLOYEES'     => new Sheet3($employees),
            'ORGANIZATIONS' => new Sheet4($organizations),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        info("Sheet {$sheetName} was skipped");
    }
}
