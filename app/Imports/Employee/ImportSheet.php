<?php

namespace App\Imports\Employee;

use App\Models\City;
use App\Models\Clients\Client;
use App\Models\Company;
use App\Models\Division;
use App\Models\Employees\Employee;
use App\Models\Employees\EmployeeCareer;
use App\Models\Employees\EmployeeContract;
use App\Models\GlobalData;
use App\Models\Office;
use App\Models\Organization;
use App\Models\Placement;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Symfony\Component\Finder\Glob;

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
        $startLine = 6;
        $totalRow = 0;
        $totalSuccess = 0;
        $totalError = 0;
        $log = [];

        for ($i = $startLine; $i < count($rows); $i++) {
            if ($nik = $this->safeGet($rows, $i, 0)) {
                $error = null;
                $uid = $this->user->id;
                $data = (object) [
                    'nik' => $nik,
                    'nickname' => $this->safeGet($rows, $i, 1),
                    'fullname' => $this->safeGet($rows, $i, 2),
                    'company_id' => $this->safeGet($rows, $i, 3),
                    'org_id' => $this->safeGet($rows, $i, 5),
                    'division_id' => $this->safeGet($rows, $i, 7),
                    'placement_id' => $this->safeGet($rows, $i, 9),
                    'birth_place' => $this->safeGet($rows, $i, 17),
                    'birth_date' => $this->parseDate($this->safeGet($rows, $i, 18)),
                    'phone' => $this->safeGet($rows, $i, 19),
                    'email' => $this->safeGet($rows, $i, 20),
                    'gender_id' => $this->safeGet($rows, $i, 21),
                    'marital_id' => $this->safeGet($rows, $i, 23),
                    'religion_id' => $this->safeGet($rows, $i, 25),
                    'join_date' => $this->parseDate($this->safeGet($rows, $i, 27)),
                    'leave_saldo' => $this->safeGet($rows, $i, 28),
                    'address' => $this->safeGet($rows, $i, 29),
                    'address_city_id' => $this->safeGet($rows, $i, 30),
                    'address_province_id' => $this->safeGet($rows, $i, 32),
                    'address_permanent' => $this->safeGet($rows, $i, 34),
                    'address_permanent_city_id' => $this->safeGet($rows, $i, 35),
                    'address_permanent_province_id' => $this->safeGet($rows, $i, 37),
                    'bank_id' => $this->safeGet($rows, $i, 39),
                    'bank_account' => $this->safeGet($rows, $i, 41),
                    'bank_alias' => $this->safeGet($rows, $i, 39)
                        ? GlobalData::where('group', 'bank')->where('id', $this->safeGet($rows, $i, 39))->first()?->alias
                        : null,
                    'emergency_relation_id' => $this->safeGet($rows, $i, 42),
                    'emergency_contact_name' => $this->safeGet($rows, $i, 44),
                    'emergency_contact_phone' => $this->safeGet($rows, $i, 45),
                    'emergency_contact_address' => $this->safeGet($rows, $i, 46),
                    'no_ktp' => $this->safeGet($rows, $i, 47),
                    'no_bpjs_kesehatan' => $this->safeGet($rows, $i, 48),
                    'no_bpjs_ketenagakerjaan' => $this->safeGet($rows, $i, 49),
                    'no_npwp' => $this->safeGet($rows, $i, 50),
                    'shift_start_time' => $this->parseTime($this->safeGet($rows, $i, 51)),
                    'shift_end_time' => $this->parseTime($this->safeGet($rows, $i, 52)),
                    'office_id' => $this->safeGet($rows, $i, 53),
                    'created_by' => $uid,
                    'updated_by' => $uid,
                ];

                // Validation
                if (Employee::where('nik', $nik)->first()) {
                    $error = "Duplicate NIK: ($nik)";
                } elseif (!Organization::find($data->org_id)) {
                    $error = "Undefined Area ($data->org_id)";
                } elseif (!Division::find($data->division_id)) {
                    $error = "Undefined Division ($data->division_id)";
                } elseif (!Company::find($data->company_id)) {
                    $error = "Undefined Company ($data->company_id)";
                } elseif (!Placement::find($data->placement_id)) {
                    $error = "Undefined Placement ($data->placement_id)";
                } elseif ($data->gender_id && !GlobalData::where('group', 'gender')->where('id', $data->gender_id)->exists()) {
                    $error = "Undefined Gender ($data->gender_id)";
                } elseif ($data->marital_id && !GlobalData::where('group', 'marital')->where('id', $data->marital_id)->exists()) {
                    $error = "Undefined Marital ($data->marital_id)";
                } elseif ($data->religion_id && !GlobalData::where('group', 'religion')->where('id', $data->religion_id)->exists()) {
                    $error = "Undefined Religion ($data->religion_id)";
                } elseif ($data->bank_id && !GlobalData::where('group', 'bank')->where('id', $data->bank_id)->exists()) {
                    $error = "Undefined Bank ($data->bank_id)";
                } elseif ($data->emergency_relation_id && !GlobalData::where('group', 'emergency_relation')->where('id', $data->emergency_relation_id)->exists()) {
                    $error = "Undefined Emergency Relation ($data->emergency_relation_id)";
                } elseif ($data->address_city_id && !City::find($data->address_city_id)) {
                    $error = "Undefined City ($data->address_city_id)";
                } elseif ($data->address_province_id && !City::find($data->address_province_id)) {
                    $error = "Undefined Province ($data->address_province_id)";
                } elseif ($data->address_permanent_city_id && !City::find($data->address_permanent_city_id)) {
                    $error = "Undefined Permanent City ($data->address_permanent_city_id)";
                } elseif ($data->address_permanent_province_id && !City::find($data->address_permanent_province_id)) {
                    $error = "Undefined Permanent Province ($data->address_permanent_province_id)";
                } elseif (!$data->fullname) {
                    $error = "Name Not Found";
                } elseif ($data->office_id && !Office::find($data->office_id)) {
                    $error = "Undefined Office ($data->office_id)";
                } else {
                    DB::beginTransaction();
                    try {
                        $employee = Employee::create((array) $data);

                        if ($statusId = $this->safeGet($rows, $i, 11)) {
                            $contract = EmployeeContract::create([
                                'employ_id' => $employee->id,
                                'status_id' => $statusId,
                                'start_date' => $this->parseDate($this->safeGet($rows, $i, 13)),
                                'end_date' => $this->parseDate($this->safeGet($rows, $i, 14)),
                            ]);
                            $employee->last_contract_id = $contract->id;
                        }

                        if ($careerId = $this->safeGet($rows, $i, 15)) {
                            $career = EmployeeCareer::create([
                                'employ_id' => $employee->id,
                                'career_id' => $careerId,
                            ]);
                            $employee->last_career_id = $career->id;
                        }

                        $citizensToCheck = [
                            ['index' => 47, 'name' => 'NOMOR KTP'],
                            ['index' => 48, 'name' => 'NOMOR BPJS KESEHATAN'],
                            ['index' => 49, 'name' => 'NOMOR BPJS KETENAGAKERJAAN'],
                            ['index' => 50, 'name' => 'NOMOR NPWP'],
                        ];

                        $citizenGroup = GlobalData::where('group', 'citizen')->get();

                        foreach ($citizensToCheck as $citizen) {
                            $value = $this->safeGet($rows, $i, $citizen['index']);

                            if ($value) {
                                $citizenType = $citizenGroup->firstWhere('name', strtoupper($citizen['name']));
                                if ($citizenType) {
                                    $employee->citizens()->create([
                                        'employ_id' => $employee->id,
                                        'citizen_id' => $citizenType->id,
                                        'value' => $value,
                                    ]);
                                } else {
                                    $error = "Undefined Citizen Type ({$citizen['name']})";
                                    break;
                                }
                            }
                        }

                        if (!$error) {
                            $employee->save();
                            DB::commit();
                            $totalSuccess++;
                        } else {
                            DB::rollback();
                        }
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

    private function parseDate($value): string
    {
        if (!$value) return date('Y-m-d');

        try {
            if (is_numeric($value)) {
                return Carbon::instance(Date::excelToDateTimeObject($value))->format('Y-m-d');
            }
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return date('Y-m-d');
        }
    }

    private function parseTime($value): ?string
    {
        if (!$value) return null;

        try {
            if (is_numeric($value)) {
                $time = Date::excelToDateTimeObject($value);
                return $time ? $time->format('H:i:s') : null;
            }

            $time = DateTime::createFromFormat('H:i', $value);
            return $time ? $time->format('H:i:s') : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function safeGet($rows, $i, $index)
    {
        return isset($rows[$i][$index]) ? $rows[$i][$index] : null;
    }
}
