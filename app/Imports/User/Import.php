<?php

namespace App\Imports\User;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Import implements WithMultipleSheets
{
    // protected $user = null;
    // protected $results = null;
    protected $user;
    protected $sheet;

    public function __construct($user)
    {
        $this->user = $user;
    }

    // public function sheets(): array{
    //     $sheet = new ImportSheet($this->user);
    //     $this->results = $sheet->result();
    //     return [
    //         'DATA' => $sheet
    //     ];
    // }

    public function sheets(): array
    {
        $this->sheet = new ImportSheet($this->user);
        return [
            'DATA' => $this->sheet
        ];
    }

    public function logs()
    {
        return $this->sheet->logs;
    }
}
