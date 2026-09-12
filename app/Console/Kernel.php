<?php

namespace App\Console;

use App\Jobs\BackupDatabaseJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('database:backup')->daily();
        $schedule->command('filetmp:remove')->daily();
        $schedule->command('appraisal:close-expired')->daily();
        $schedule->command('leave:reset-annual-balance')->yearlyOn(1, 1, '00:00');

        $schedule->job(new BackupDatabaseJob)
            ->dailyAt('00:00')
            ->withoutOverlapping()
            ->onOneServer();
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
