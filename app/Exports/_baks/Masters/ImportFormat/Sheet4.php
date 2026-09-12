<?php
namespace App\Exports\_baks\Masters\ImportFormat;


use App\Models\Region;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet4 implements FromView, WithTitle
{
    public function __construct(){

    }

    public function view(): View {
        return view('exports.excel.master.import_format.sheet4', [
            'cities' => Region::all()
        ]);
    }

    public function title(): string
    {
        return 'CITIES';
    }
}
