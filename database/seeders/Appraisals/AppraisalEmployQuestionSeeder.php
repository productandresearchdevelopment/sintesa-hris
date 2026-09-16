<?php

namespace Database\Seeders\Appraisals;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppraisalEmployQuestionSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('iq_appraisal_employ_question')->truncate();

        $appraisalEmploys = DB::table('iq_appraisal_employ')->get();

        // Target scores per employee
        $scoresMap = [
            'a0000000-0000-0000-0001-000000000002' => ['score' => 9.0, 'note' => 'Kinerja sangat baik dan konsisten sesuai target'],
            'a0000000-0000-0000-0001-000000000003' => ['score' => 8.5, 'note' => 'Kinerja baik dan memuaskan'],
            'a0000000-0000-0000-0001-000000000004' => ['score' => 9.0, 'note' => 'Pencapaian tugas sangat memuaskan'],
            'a0000000-0000-0000-0002-000000000002' => ['score' => 9.0, 'note' => 'Kinerja operasional sangat baik'],
            'a0000000-0000-0000-0002-000000000003' => ['score' => 8.5, 'note' => 'Pencapaian tugas baik dan disiplin'],
            'a0000000-0000-0000-0002-000000000004' => ['score' => 9.0, 'note' => 'Kinerja sangat memuaskan dan loyal'],
        ];

        foreach ($appraisalEmploys as $ae) {
            $questions = DB::table('iq_appraisal_question')
                ->where('template_id', $ae->template_id)
                ->get();

            $cfg = $scoresMap[$ae->id] ?? ['score' => 9.0, 'note' => 'Kinerja Sangat Baik'];
            $score = $cfg['score'];
            $note = $cfg['note'];

            foreach ($questions as $q) {
                DB::table('iq_appraisal_employ_question')->insert([
                    'appraisal_employ_id' => $ae->id,
                    'question_id' => $q->id,
                    'system_info' => null,
                    'evaluator1_value' => $score,
                    'evaluator1_point' => $score,
                    'evaluator1_weight' => $q->weight ?? 10.0,
                    'evaluator1_note' => $note,
                    'evaluator2_value' => $score,
                    'evaluator2_point' => $score,
                    'evaluator2_weight' => $q->weight ?? 10.0,
                    'evaluator2_note' => $note,
                ]);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
