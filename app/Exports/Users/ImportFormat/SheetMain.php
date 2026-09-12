<?php
namespace App\Exports\Users\ImportFormat;

use App\Models\Owners\Owner;
use App\Models\Owners\OwnerArea;
use App\Models\Vendors\Vendor;
use App\Models\Vendors\VendorArea;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class SheetMain implements FromView, WithTitle
{
    private $roles;

    public function __construct($roles){
        $this->roles = $roles;
    }

    public function view(): View {
        $addrow = 20;
        $countRoles = count($this->roles) + $addrow;

        return view('exports.excel.users.import_format.sheet_main', [
            'startRow' => 5,
            'countRoles' => $countRoles,
        ]);
    }

    public function title(): string {
        return 'DATA';
    }
}

