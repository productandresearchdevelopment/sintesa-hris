<?php
namespace App\Exports\_baks\Masters\ImportFormat;

use App\Models\Owners\Owner;
use App\Models\Projects\Project;
use App\Models\Vendors\Vendor;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExcelFormat implements WithMultipleSheets {
    private $owner;
    private $project;
    private $vendor;

    public function __construct($owner, $project, $vendor){
        $this->owner = Owner::find($owner);
        $this->project = Project::find($project);
        $this->vendor = Vendor::find($vendor);
    }

    public function sheets(): array {
        $owner = $this->owner;
        $project = $this->project;
        $vendor = $this->vendor;

        return [
            'DATA' => new Sheet1($owner, $project, $vendor),
            'AREA_VENDOR' => new Sheet2($vendor),
            'CLIENT_GROUP' => new Sheet3($owner),
            'CITIES' => new Sheet4(),
        ];
    }

    public function onUnknownSheet($sheetName) {
        info("Sheet {$sheetName} was skipped");
    }
}
