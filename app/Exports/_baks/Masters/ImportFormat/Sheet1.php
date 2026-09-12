<?php
namespace App\Exports\_baks\Masters\ImportFormat;

use App\Models\Owners\OwnerArea;
use App\Models\Region;
use App\Models\Vendors\VendorArea;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Sheet1 implements FromView, WithTitle, WithColumnFormatting
{
    private $owner;
    private $project;
    private $vendor;

    public function __construct($owner, $project, $vendor){
        $this->owner = $owner;
        $this->project = $project;
        $this->vendor = $vendor;
    }

    public function view(): View {
        $countVendorArea = VendorArea::where('vendor_id', $this->vendor->id)->count();
        $countOwnerArea = OwnerArea::where('owner_id', $this->owner->id)->count();
        $countCity = Region::count();

        return view('exports.excel.master.import_format.sheet1', [
            'owner' => $this->owner,
            'project' => $this->project,
            'vendor' => $this->vendor,
            'countVendorArea' => $countVendorArea,
            'countOwnerArea' => $countOwnerArea,
            'countCity' => $countCity,
            'extraFields' => $this->getExtraFields()
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'N' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'O' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

    public function title(): string {
        return 'DATA';
    }

    private function getExtraFields(){
        $result = [];
        if(isset($this->project->property->extrafield)){
            if($extraFields = $this->project->property->extrafield){
                if(count($extraFields)){
                    foreach ($extraFields AS $extra){
                        $items = [];
                        $count = 0;
                        foreach ($extra->items AS $item){
                            if($item->type != 'file' && $item->type != 'signature') {
                                $items[] = (object)[
                                    'type' => $item->type,
                                    'index' => $item->index,
                                    'label' => $item->label,
                                    'default' => $item->default,
                                    'required' => $item->required,
                                ];
                                $count++;
                            }
                        }

                        if($count) {
                            $result[] = (object)[
                                'type' => $extra->type,
                                'label' => $extra->label,
                                'items' => $items,
                                'count' => $count,
                            ];
                        }
                    }
                }
            }
        }
        return $result;
    }
}

