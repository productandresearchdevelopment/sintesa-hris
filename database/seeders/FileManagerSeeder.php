<?php

namespace Database\Seeders;

use App\Models\FileManager as ModelsFileManager;
use Illuminate\Database\Seeder;

class FileManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $root1 = [
            'parent_id' => null,
            'type' => 1,
            'path' => '/1/',
            'name' => 'Root',
        ];
        // $root2 = [
        //     'parent_id' => null,
        //     'type' => 1,
        //     'path' => '/2/',
        //     'name' => 'Helpdesk',
        // ];

        ModelsFileManager::create($root1);
        // ModelsFileManager::create($root2);
    }
}
