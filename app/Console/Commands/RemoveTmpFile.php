<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;

class RemoveTmpFile extends Command
{
    protected $signature = 'filetmp:remove';
    protected $description = 'Menghapus Temporary File';

    public function __construct(){
        parent::__construct();
    }

    public function handle(){
        $this->info('Removing File Temporary File ...');
        $files = Storage::disk('local')->files('tmp');
        $total = 0;
        foreach ($files AS $file){
            $path = Storage::disk('local')->path("$file");
            unlink($path);
            $total++;
        }
        $this->info("TOTAL: $total File");
        glog('remove-temporary-file', 'scheduler', [
            'server' => config('site.server_name'),
            'timestamp' => date('Y-m-d H:i:s'),
            'total' => $total
        ]);
    }
}
