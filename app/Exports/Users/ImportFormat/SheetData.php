<?php
namespace App\Exports\Users\ImportFormat;


use App\Models\Vendors\VendorArea;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class SheetData implements FromView, WithTitle
{
    private $data;
    private $title;

    public function __construct($title, $data){
        $this->title = $title;
        $this->data = $data;
    }

    public function view(): View {
        return view('exports.excel.users.import_format.sheet_data', [
            'title' => $this->title,
            'data' => $this->data,
        ]);
    }

    public function title(): string {
        return $this->title;
    }
}
