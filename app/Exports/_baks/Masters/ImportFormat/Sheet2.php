<?php
namespace App\Exports\_baks\Masters\ImportFormat;


use App\Models\Vendors\VendorArea;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet2 implements FromView, WithTitle
{
    private $vendor;

    public function __construct($vendor){
        $this->vendor = $vendor;
    }

    public function view(): View {
        $areas = VendorArea::where('vendor_id', $this->vendor->id)->orderBy('path')->get();
        return view('exports.excel.master.import_format.sheet2', [
            'vendor' => $this->vendor,
            'areas' => $areas
        ]);
    }

    public function title(): string
    {
        return 'VENDOR_AREA';
    }
}
