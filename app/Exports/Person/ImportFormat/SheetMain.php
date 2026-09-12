<?php
namespace App\Exports\Person\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class SheetMain implements FromView, WithTitle
{
    public function view(): View {
        return view('exports.excel.person.import_format.main', [
            'startRow' => 5,
            'countRoles' => 100,
        ]);
    }

    public function title(): string {
        return 'DATA';
    }
}

