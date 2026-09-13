<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobsSeeder extends Seeder
{
    public function run()
    {
        $payload = json_encode([
            'uuid' => '5395cadd-3df5-4cd6-ab2e-886aa1945914',
            'displayName' => 'App\\Jobs\\BackupDatabaseJob',
            'job' => 'Illuminate\\Queue\\CallQueuedHandler@call',
            'maxTries' => null,
            'maxExceptions' => null,
            'failOnTimeout' => false,
            'backoff' => null,
            'timeout' => null,
            'retryUntil' => null,
            'data' => [
                'commandName' => 'App\\Jobs\\BackupDatabaseJob',
                'command' => 'O:26:"App\\Jobs\\BackupDatabaseJob":0:{}',
            ],
        ], JSON_UNESCAPED_SLASHES);

        $jobData = [
            'id' => 4782429,
            'queue' => 'default',
            'payload' => $payload,
            'attempts' => 1,
            'reserved_at' => 1789059608,
            'available_at' => 1789059606,
            'created_at' => 1789059606,
        ];

        DB::table('jobs')->updateOrInsert(
            ['id' => $jobData['id']],
            $jobData
        );
    }
}
