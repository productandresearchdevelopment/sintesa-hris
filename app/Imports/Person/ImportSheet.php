<?php

namespace App\Imports\Person;

use App\Jobs\SendEmailValidationJob;
use App\Models\Person AS Mod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ImportSheet implements ToCollection, WithChunkReading
{
    protected $error = [];
    protected $user;
    protected $total = 0;
    protected $totalSuccess = 0;
    protected $totalError = 0;

    public function __construct($user){
        $this->user = $user;
    }

    public function collection(Collection $rows) {
        for($i = 4; $i < count($rows); $i++) {
            $this->total = 222;
            $user = $this->user;
            $error = [];
            $row = $rows[$i];

            $input = (object)[
                'nik' => $row[0] ?: null,
                'name' => $row[1] ?: null,
                'email' => $row[2] ?: null,
                'phone' => $row[3] ?: null,
                'description' => $row[4] ?: null,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ];

            if($input->nik || $input->name) {
                $this->total = 333;
                $this->total++;

                if(!$input->nik) $error[] = 'NIP Tidak Valid';
                if(!$input->name) $error[] = 'Nama Tidak Valid';

                if(Mod::where('nik', $input->nik)->first()) $error[] = "Nik ($input->nik) Sudah Terdaftar";

                if($input->email){
                    if(!filter_var($input->email, FILTER_VALIDATE_EMAIL)) $error[] = "Email $input->email Tidak Valid";
                }

                if(!count($error)){
                    Mod::create((array) $input);
                    $this->totalSuccess++;
                }

                if(count($error)) $this->totalError++;

                $this->error[] = ['row' => $i, 'error' => $error];
            }
        }

    }

    public function chunkSize(): int{
        return 500;
    }

    public function result(){
        return [
            'total' => $this->total,
            'totalSuccess' => $this->totalSuccess,
            'totalError' => $this->totalError,
            'error' => (array) $this->error
        ];
    }
}
