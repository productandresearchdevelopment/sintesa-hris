<?php
namespace App\Exports\Users\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class SheetTree implements FromView, WithTitle
{
    private $title;
    private $data;

    public function __construct($title, $data){
        $this->data = $data;
        $this->title = $title;
    }

    public function view(): View {
        return view('exports.excel.users.import_format.sheet_tree', [
            'title' => $this->title,
            'data' => $this->data
        ]);
    }

    public function title(): string {
        return $this->title;
    }
}
