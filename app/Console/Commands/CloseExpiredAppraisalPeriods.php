<?php

namespace App\Console\Commands;

use App\Models\Appraisals\AppraisalPeriod;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CloseExpiredAppraisalPeriods extends Command
{
    protected $signature = 'appraisal:close-expired';
    protected $description = 'Menutup appraisal period yang telah melewati end_date';

    public function handle()
    {
        $today = Carbon::now()->toDateString();

        $updated = AppraisalPeriod::whereDate('end_date', '<', $today)
            ->where('is_closed', 0)
            ->update(['is_closed' => 1]);

        if ($updated > 0) {
            $this->info("$updated appraisal period telah ditutup.");
        } else {
            $this->info("Tidak ada appraisal period yang perlu ditutup.");
        }
    }
}
