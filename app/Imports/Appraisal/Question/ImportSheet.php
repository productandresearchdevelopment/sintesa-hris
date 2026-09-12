<?php

namespace App\Imports\Appraisal\Question;

use App\Models\Appraisals\AppraisalQuestion;
use App\Models\Appraisals\AppraisalQuestionCategory;
use App\Models\Appraisals\AppraisalQuestionTemplate;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ImportSheet implements ToCollection, WithChunkReading
{
    public $logs = [];
    private $user = null;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function collection(Collection $rows)
    {
        $startLine = 4;
        $totalRow = 0;
        $totalSuccess = 0;
        $totalError = 0;
        $log = [];
        $validData = [];

        $validCategoryIds = AppraisalQuestionCategory::pluck('id')->toArray();
        $validTemplateIds = AppraisalQuestionTemplate::pluck('id')->toArray();

        for ($i = $startLine; $i < count($rows); $i++) {
            if ($question = $rows[$i][0]) {
                $error = null;
                $uid = $this->user->id;

                $data = [
                    'question' => $question,
                    'category_id' => $rows[$i][1],
                    'template_id' => $rows[$i][3],
                    'group_kpi' => $rows[$i][5],
                    'formula_description' => $rows[$i][6],
                    'weight' => $rows[$i][7],
                    'created_by' => $uid,
                    'updated_by' => $uid,
                ];

                if (!in_array($data['category_id'], $validCategoryIds)) {
                    $error = "Undefined Category ({$data['category_id']})";
                } elseif (!in_array($data['template_id'], $validTemplateIds)) {
                    $error = "Undefined Template ({$data['template_id']})";
                } elseif (!$data['question'] || !$data['group_kpi'] || !$data['formula_description'] || !$data['weight']) {
                    $error = "Missing required fields";
                }

                if ($error) {
                    $log[] = [
                        'row' => ($i + 1),
                        'success' => false,
                        'message' => $error
                    ];
                    $totalError++;
                } else {
                    $validData[] = $data;
                    $totalSuccess++;
                }

                $totalRow++;
            }
        }

        if (!empty($validData)) {
            try {
                DB::beginTransaction();
                AppraisalQuestion::insert($validData);
                DB::commit();
            } catch (QueryException $e) {
                DB::rollBack();
                $log[] = [
                    'row' => 'BATCH_INSERT',
                    'success' => false,
                    'message' => $e->getMessage()
                ];
                $totalError += count($validData);
                $totalSuccess -= count($validData);
            }
        }

        $this->logs = [
            'totalRow' => $totalRow,
            'totalSuccess' => $totalSuccess,
            'totalError' => $totalError,
            'errorLog' => $log
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
