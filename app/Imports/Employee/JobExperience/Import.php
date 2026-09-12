<?php

namespace App\Imports\Employee\JobExperience;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Import implements WithMultipleSheets
{
    protected $user;
    protected $sheet;

    public function __construct($user)
    {
        $this->user = $user;
    }

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

    public function getData()
    {
        return $this->sheet->data;
    }
}
