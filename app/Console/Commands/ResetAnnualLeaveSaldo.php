<?php

namespace App\Console\Commands;

use App\Models\Employees\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetAnnualLeaveSaldo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:reset-annual-balance {--amount=12 : Hak cuti tahunan standar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset dan perbarui saldo cuti tahunan pegawai setiap awal tahun baru (1 Januari)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $defaultAmount = (int) $this->option('amount');
        if ($defaultAmount <= 0) {
            $defaultAmount = 12;
        }

        $employees = Employee::all();
        $updatedCount = 0;

        $this->info("Memulai pembaharuan saldo cuti tahunan untuk {$employees->count()} pegawai (Jatah Dasar: {$defaultAmount} hari)...");

        foreach ($employees as $employee) {
            $oldSaldo = (int) ($employee->leave_saldo ?? 0);
            $newSaldo = ($oldSaldo < 0) ? ($defaultAmount + $oldSaldo) : $defaultAmount;

            $employee->update(['leave_saldo' => $newSaldo]);
            $updatedCount++;

            Log::info("Annual Leave Saldo Updated for Employee [ID: {$employee->id}, Name: {$employee->fullname}]: Old Saldo = {$oldSaldo}, New Saldo = {$newSaldo}");
        }

        $this->info("Berhasil memperbarui saldo cuti tahunan untuk {$updatedCount} pegawai.");
        Log::info("Command leave:reset-annual-balance executed successfully for {$updatedCount} employees.");
    }
}
