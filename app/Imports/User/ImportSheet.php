<?php

namespace App\Imports\User;

use App\Models\Employees\Employee;
use App\Models\Organization;
use App\SystemModels\Auth\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ImportSheet implements ToCollection, WithChunkReading
{
    public $logs = [];
    private $user = null;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function collection(Collection $rows)
    {
        $startLine = 4;
        $totalRow = 0;
        $totalSuccess = 0;
        $totalError = 0;
        $log = [];

        for ($i = $startLine; $i < count($rows); $i++) {
            if ($name = $this->safeGet($rows, $i, 0)) {
                $error = null;
                $uid = $this->user->id;

                // Ambil nilai mentah dari sheet
                $nikFromSheet = $this->safeGet($rows, $i, 8);
                $orgIdFromSheet = $this->safeGet($rows, $i, 6);

                // Default data object
                $data = (object) [
                    'name' => $name,
                    'username' => $this->safeGet($rows, $i, 1),
                    'email' => $this->safeGet($rows, $i, 2),
                    'role_id' => $this->safeGet($rows, $i, 3),
                    'password' => $this->safeGet($rows, $i, 5),
                    'organization_id' => $orgIdFromSheet,
                    'employ_id' => null,
                    'email_validation_code' => rand(100000, 999999),
                    'email_validation_sent_at' => Carbon::now(),
                    'created_by' => $uid,
                    'updated_by' => $uid,
                ];

                $employee = Employee::where('nik', $nikFromSheet)->first();
                $organization = Organization::find($orgIdFromSheet);

                if (User::where('username', $data->username)->exists()) {
                    $error = "Duplicate username: ($data->username)";
                } elseif (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
                    $error = "Invalid email: ($data->email)";
                } elseif (!$employee) {
                    $error = "Employee with NIK ($nikFromSheet) not found";
                } elseif (!$organization) {
                    $error = "Undefined Organization ID ($orgIdFromSheet)";
                } else {
                    $data->employ_id = $employee->id;
                    $data->password = Hash::make($data->password);

                    DB::beginTransaction();
                    try {
                        $user = User::create((array) $data);
                        $user->save();
                        DB::commit();
                        $totalSuccess++;
                    } catch (QueryException $e) {
                        DB::rollback();
                        $error = $e->getMessage();
                    }
                }

                if ($error) {
                    $log[] = [
                        'row' => ($i + 1),
                        'success' => false,
                        'message' => $error,
                    ];
                    $totalError++;
                }

                $totalRow++;
            }
        }

        $this->logs = [
            'totalRow' => $totalRow,
            'totalSuccess' => $totalSuccess,
            'totalError' => $totalError,
            'errorLog' => $log,
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }

    private function safeGet($rows, $i, $index)
    {
        return isset($rows[$i][$index]) ? trim($rows[$i][$index]) : null;
    }
}
