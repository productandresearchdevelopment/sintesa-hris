<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ZipArchive;
use Carbon\Carbon;

class BackupDatabaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        try {
            $date = now()->format('Ymd');

            $filename = "database_backup_{$date}.sql";
            $zipname = "database_backup_{$date}.zip";
            $backupPath = storage_path('backups');

            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            $sqlFile = "{$backupPath}/{$filename}";
            $zipFile = "{$backupPath}/{$zipname}";
            $db = config('database.connections.mysql');

            $command = sprintf(
                'mysqldump --user=%s --password="%s" --host=%s %s > %s',
                $db['username'],
                $db['password'],
                $db['host'],
                $db['database'],
                $sqlFile
            );

            Log::info('Running backup command', ['cmd' => $command]);

            exec($command, $output, $result);
            if ($result !== 0) {
                Log::error('mysqldump gagal', $output);
                throw new \Exception('Dump database gagal');
            }

            if (!file_exists($sqlFile)) {
                throw new \Exception('File SQL tidak terbentuk');
            }

            $zip = new ZipArchive;
            if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
                $zip->addFile($sqlFile, $filename);
                $zip->close();
            } else {
                throw new \Exception('Gagal membuat ZIP');
            }

            unlink($sqlFile);
            $this->cleanupOldBackups($backupPath);
            Log::info('Backup database berhasil', [
                'file' => $zipFile
            ]);
        } catch (\Throwable $e) {
            Log::error('Backup DB gagal: ' . $e->getMessage());
        }
    }

    private function cleanupOldBackups($path)
    {
        $files = collect(File::files($path))
            ->sortBy(fn($file) => $file->getCTime());

        foreach ($files as $file) {
            $fileDate = Carbon::createFromTimestamp($file->getCTime());

            if ($fileDate->diffInDays(now()) > 365) {
                File::delete($file->getRealPath());
            }
        }
    }
}
