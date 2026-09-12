<?php
namespace App\Exports\_baks\WorkOrders\ImportFormat;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class Sheet1 implements FromView, WithTitle
{
    private $owner;
    private $project;
    private $activity;
    private $status;

    public function __construct($owner, $project, $activity, $status){
        $this->owner = $owner;
        $this->project = $project;
        $this->activity = $activity;
        $this->status = $status;
    }

    public function view(): View {
        return view('exports.excel.wo.import_format.sheet1', [
            'owner' => $this->owner,
            'project' => $this->project,
            'activity' => $this->activity,
            'status' => $this->status,
            'extraFields' => $this->getExtraFields()
        ]);
    }

    public function title(): string {
        return 'DATA';
    }

    private function getExtraFields(){
        $result = [];
        if(isset($this->status->property->extrafield)){
            if($extraFields = $this->status->property->extrafield){
                if(count($extraFields)){
                    foreach ($extraFields AS $extra){
                        $items = [];
                        $count = 0;
                        foreach ($extra->items AS $item){
                            if($item->type != 'file' && $item->type != 'signature' && $item->type != 'hidden') {
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

