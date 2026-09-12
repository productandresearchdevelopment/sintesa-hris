<?php

namespace App\Imports\Person;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Import implements WithMultipleSheets
{
    protected $user = null;
    protected $results = null;

    public function __construct($user){
        $this->user = $user;
    }

    public function sheets(): array{
        $sheet = new ImportSheet($this->user);
        $this->results = $sheet->result();
        return ['DATA' => $sheet];
    }

    public function get(){
        return $this->results;
    }
}
