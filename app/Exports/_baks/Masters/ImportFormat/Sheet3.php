<?php
namespace App\Exports\_baks\Masters\ImportFormat;


use App\Models\Owners\OwnerArea;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet3 implements FromView, WithTitle
{
    private $owner;
    public function __construct($owner){
        $this->owner = $owner;
    }

    public function view(): View {
        $areas = OwnerArea::where('owner_id', $this->owner->id)->orderBy('path')->get();
        return view('exports.excel.master.import_format.sheet3', [
            'owner' => $this->owner,
            'areas' => $areas
        ]);
    }

    public function title(): string
    {
        return 'CLIENT_GROUP';
    }
}
