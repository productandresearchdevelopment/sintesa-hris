<?php

namespace App\Exports\Employee\ImportFormat;

use App\Models\City;
use App\Models\Company;
use App\Models\Division;
use App\Models\GlobalData;
use App\Models\Office;
use App\Models\Organization;
use App\Models\Placement;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Format implements WithMultipleSheets
{

    public function __construct() {}

    public function sheets(): array
    {
        $organization = Organization::all();
        $division = Division::all();
        $company = Company::all();
        $placement = Placement::all();
        $gender = GlobalData::where('group', 'gender')->get();
        $marital = GlobalData::where('group', 'marital')->get();
        $religion = GlobalData::where('group', 'religion')->get();
        $bank = GlobalData::where('group', 'bank')->get();
        $emergency_relation = GlobalData::where('group', 'emergency_relation')->get();
        $city = City::select('id', 'city')->get();
        $province = City::select('id', 'province')->get();
        $contract = GlobalData::where('group', 'contract_status')->get();
        $career = GlobalData::where('group', 'career')->get();
        $offices = Office::all();

        return [
            'DATA' => new Sheet1($organization, $division, $company, $placement, $gender, $marital, $religion, $bank, $emergency_relation, $city, $province, $contract, $career, $offices),
            'COMPANY' => new Sheet2($company),
            'ORGANIZATION' => new Sheet3($organization),
            'DIVISION' => new Sheet4($division),
            'PLACEMENT' => new Sheet5($placement),
            'CONTRACT' => new Sheet6($contract),
            'CAREER' => new Sheet7($career),
            'GENDER' => new Sheet8($gender),
            'MARITAL' => new Sheet9($marital),
            'RELIGION' => new Sheet10($religion),
            'BANK' => new Sheet11($bank),
            'EMERGENCY_RELATION' => new Sheet12($emergency_relation),
            'CITY' => new Sheet13($city),
            'PROVINCE' => new Sheet14($province),
            'OFFICE' => new Sheet15($offices)
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        info("Sheet {$sheetName} was skipped");
    }
}
