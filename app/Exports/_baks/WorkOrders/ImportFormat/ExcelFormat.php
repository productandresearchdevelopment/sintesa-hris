<?php
namespace App\Exports\_baks\WorkOrders\ImportFormat;

use App\Models\Owners\Owner;
use App\Models\Projects\Activity;
use App\Models\Projects\ActivityStatus;
use App\Models\Projects\Project;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExcelFormat implements WithMultipleSheets {
    private $owner;
    private $project;
    private $activity;
    private $status;

    public function __construct($owner, $project, $activity, $status){
        $this->owner = Owner::find($owner);
        $this->project = Project::find($project);
        $this->activity = Activity::find($activity);
        $this->status = ActivityStatus::find($status);
    }

    public function sheets(): array {
        $owner = $this->owner;
        $project = $this->project;
        $activity = $this->activity;
        $status = $this->status;

        return [
            'DATA' => new Sheet1($owner, $project, $activity, $status),
        ];
    }

    public function onUnknownSheet($sheetName) {
        info("Sheet {$sheetName} was skipped");
    }
}
