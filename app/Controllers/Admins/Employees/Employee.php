<?php

namespace App\Controllers\Admins\Employees;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Employees\Employee as Mod;
use App\Models\Employees\EmployeeCareer as ModCareer;
use App\Models\Employees\EmployeeEducation as ModEducation;
use App\Models\Employees\EmployeeFamily as ModFamily;
use App\Models\Employees\EmployeeJobExperience as ModJobExperience;
use App\Models\Employees\EmployeeTraining as ModTraining;
use App\Models\Employees\EmployeeContract as ModContract;
use App\Models\Employees\EmployeeCitizen as ModCitizen;
use App\Models\Employees\EmployeeContract as EmployeeContract;
use App\Models\Employees\EmployeeCareer as EmployeeCareer;

use App\Models\Organization;
use App\Models\Division;
use App\Models\Company;
use App\Models\Placement;
use App\Models\City;
use App\Models\GlobalData;

use App\Libraries\FileUpload;
use App\Libraries\Query;
use App\Exports\Employee\ImportFormat\Format;
use App\Exports\Employee\ImportFormatContract\Format as FormatContract;
use App\Exports\Employee\ImportFormatCareer\Format as FormatCareer;
use App\Exports\Employee\ImportFormatTraining\Format as FormatTraining;
use App\Exports\Employee\ImportFormatJobExperience\Format as FormatJobExperience;
use App\Exports\Employee\ImportFormatFamily\Format as FormatFamily;
use App\Exports\Employee\ImportFormatEducation\Format as FormatEducation;
use App\Exports\Employee\ImportFormatCitizen\Format as FormatCitizen;
use App\Imports\Employee\Import;
use App\Imports\Employee\Contract\Import as ImportContract;
use App\Imports\Employee\Career\Import as ImportCareer;
use App\Imports\Employee\Training\Import as ImportTraining;
use App\Imports\Employee\JobExperience\Import as ImportJobExperience;
use App\Imports\Employee\Family\Import as ImportFamily;
use App\Imports\Employee\Education\Import as ImportEducation;
use App\Imports\Employee\Citizen\Import as ImportCitizen;
use App\Libraries\ExportExcel;
use App\SystemModels\Globals\Upload;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;

class Employee extends Controller
{
    private function pdfRelations(): array
    {
        return [
            'gender',
            'marital',
            'religion',
            'bank',
            'emergency_relation',
            'photo',

            'address_city',
            'address_province',
            'address_permanent_city',
            'address_permanent_province',

            'organization',
            'division',
            'company',
            'placement',

            'contracts',
            'contracts.status',
            'contracts.file',

            'citizens',
            'citizens.citizen',
            'citizens.file',

            'educations',
            'educations.education',
            'educations.major',
            'educations.file',

            'families',
            'families.relation',
            'families.occupation',

            'job_experiences',

            'careers',
            'careers.career',
            'careers.placement',
            'careers.organization',
            'careers.file',

            'trainings',
            'trainings.file',

            'last_contract',
            'last_contract.status',
            'last_career',
            'last_career.career',
        ];
    }

    private function isUploadedFile($value): bool
    {
        return $value instanceof \Illuminate\Http\UploadedFile;
    }

    private function normalizeDates(array $payload, array $dateFields): array
    {
        foreach ($dateFields as $f) {
            if (!array_key_exists($f, $payload) || empty($payload[$f])) {
                $payload[$f] = null;
                continue;
            }

            try {
                $payload[$f] = Carbon::parse($payload[$f])->format('Y-m-d');
            } catch (\Throwable $e) {
                $payload[$f] = null;
            }
        }

        return $payload;
    }

    private function processDetail(
        array $items,
        string $employeeId,
        string $modelClass,
        ?string $uploadFolder,
        array $allowedFields,
        array $dateFields = [],
        ?string $fileColumn = 'file_id',
        array $booleanFields = []
    ): void {
        foreach ($items as $item) {
            if (!$item) continue;

            $item = $this->normalizeDates($item, $dateFields);
            $newFileId = null;
            $hasFileColumn = !empty($fileColumn);

            if (
                $hasFileColumn
                && isset($item['files'])
                && $this->isUploadedFile($item['files'])
                && $uploadFolder
            ) {
                $newFileId = FileUpload::upload($item['files'], $uploadFolder);
            }

            $data = array_intersect_key($item, array_flip($allowedFields));
            foreach ($booleanFields as $bf) {
                if (array_key_exists($bf, $data)) {
                    $v = $data[$bf];
                    $data[$bf] = ($v === true || $v === 1 || $v === '1' || $v === 'on' || $v === 'yes');
                }
            }

            if (isset($item['id'])) {
                $existing = $modelClass::find($item['id']);
                if ($existing) {
                    if ($hasFileColumn && $newFileId && !empty($existing->{$fileColumn})) {
                        FileUpload::removeFileById($existing->{$fileColumn});
                    }

                    if ($hasFileColumn) {
                        $data[$fileColumn] = $newFileId ?? ($existing->{$fileColumn} ?? null);
                    }

                    $existing->update($data);
                }
            } else {
                $data['employ_id'] = $employeeId;
                if ($hasFileColumn) {
                    $data[$fileColumn] = $newFileId ?? null;
                }
                $modelClass::create($data);
            }
        }
    }

    private function deleteRelatedItems($existingItems, $currentItems, $modelClass, $fileColumn = 'file_id'): void
    {
        $itemsToDelete = $existingItems->filter(function ($existing) use ($currentItems) {
            return !$currentItems->contains(function ($current) use ($existing) {
                return isset($current['id']) && $current['id'] == $existing->id;
            });
        });

        foreach ($itemsToDelete as $item) {
            if ($fileColumn && property_exists($item, $fileColumn) && !empty($item->{$fileColumn})) {
                FileUpload::removeFileById($item->{$fileColumn});
            }
            $item->forceDelete();
        }
    }

    private function processEmployeeDetail(array $data, string $employeeId): void
    {
        $maps = [
            'career' => [
                'model'       => ModCareer::class,
                'folder'      => 'employee-career',
                'fields'      => ['career_id', 'placement_id', 'date', 'org_id', 'description'],
                'dateFields'  => ['date'],
                'fileColumn'  => 'file_id',
            ],
            'education' => [
                'model'       => ModEducation::class,
                'folder'      => 'employee-education',
                'fields'      => ['education_id', 'major_id', 'institution', 'graduate', 'ipk', 'description'],
                'dateFields'  => [],
                'fileColumn'  => 'file_id',
            ],
            'family' => [
                'model'       => ModFamily::class,
                'folder'      => null,
                'fields'      => ['relation_id', 'nik', 'name', 'birth_date', 'occupation_id', 'occupation_description', 'address', 'phone'],
                'dateFields'  => ['birth_date'],
                'fileColumn'  => null,
            ],
            'jobExperience' => [
                'model'       => ModJobExperience::class,
                'folder'      => null,
                'fields'      => ['name', 'start_date', 'end_date', 'job_title', 'job_description', 'salary', 'reason_leaving', 'description'],
                'dateFields'  => ['start_date', 'end_date'],
                'fileColumn'  => null,
            ],
            'training' => [
                'model'         => ModTraining::class,
                'folder'        => 'employee-training',
                'fields'        => ['title', 'location', 'start_date', 'end_date', 'description', 'is_internal', 'is_certification'],
                'dateFields'    => ['start_date', 'end_date'],
                'fileColumn'    => 'file_id',
                'booleanFields' => ['is_internal', 'is_certification'],
            ],
            'contract' => [
                'model'       => ModContract::class,
                'folder'      => 'employee-contract',
                'fields'      => ['status_id', 'start_date', 'end_date', 'description'],
                'dateFields'  => ['start_date', 'end_date'],
                'fileColumn'  => 'file_id',
            ],
            'citizen' => [
                'model'       => ModCitizen::class,
                'folder'      => 'employee-citizen',
                'fields'      => ['citizen_id', 'value', 'description'],
                'dateFields'  => [],
                'fileColumn'  => 'file_id',
            ],
        ];

        foreach ($maps as $key => $cfg) {
            $this->processDetail(
                $data[$key] ?? [],
                $employeeId,
                $cfg['model'],
                $cfg['folder'],
                $cfg['fields'],
                $cfg['dateFields'],
                $cfg['fileColumn'] ?? 'file_id',
                $cfg['booleanFields'] ?? []
            );
        }
    }

    private function baseValidationRules(): array
    {
        return [
            'org_id'                          => 'required|integer|exists:iq_org,id',
            'division_id'                     => 'required|integer|exists:iq_division,id',
            'company_id'                      => 'required|integer|exists:iq_company,id',
            'placement_id'                    => 'required|integer|exists:iq_placement,id',
            'nik'                             => 'required|string',
            'nickname'                        => 'nullable|string',
            'fullname'                        => 'required|string',
            'birth_place'                     => 'required|string',
            'birth_date'                      => 'required|date',
            'phone'                           => 'required|string',
            'email'                           => 'required|string',
            'gender_id'                       => 'required|integer|exists:iq_global_data,id',
            'marital_id'                      => 'required|integer|exists:iq_global_data,id',
            'religion_id'                     => 'required|integer|exists:iq_global_data,id',
            'join_date'                       => 'required|date',
            'leave_saldo'                     => 'required|numeric',
            'address'                         => 'required|string',
            'address_city_id'                 => 'required|integer|exists:iq_city,id',
            'address_province_id'             => 'required|integer|exists:iq_city,id',
            'address_permanent'               => 'nullable|string',
            'address_permanent_city_id'       => 'nullable|integer|exists:iq_city,id',
            'address_permanent_province_id'   => 'nullable|integer|exists:iq_city,id',
            'bank_id'                         => 'required|integer|exists:iq_global_data,id',
            'bank_account'                    => 'required|string',
            'emergency_relation_id'           => 'nullable|integer|exists:iq_global_data,id',
            'emergency_contact_name'          => 'nullable|string',
            'emergency_contact_phone'         => 'nullable|string',
            'emergency_contact_address'       => 'nullable|string',
            'fileInputGeneral'                => 'nullable',
            'contract.*'                      => 'nullable',
            'citizen.*'                       => 'nullable',
            'family.*'                        => 'nullable',
            'jobExperience.*'                 => 'nullable',
            'career.*'                        => 'nullable',
            'training.*'                      => 'nullable',
            'office_id'                       => 'nullable|integer|exists:iq_office,id',
            'shift_start_time'                => 'nullable|date_format:H:i',
            'shift_end_time'                  => 'nullable|date_format:H:i',
        ];
    }

    private function syncDetails(Mod $employee, array $detail): void
    {
        $this->deleteRelatedItems(ModCareer::where('employ_id', $employee->id)->get(),       collect($detail['career']),        ModCareer::class);
        $this->deleteRelatedItems(ModTraining::where('employ_id', $employee->id)->get(),     collect($detail['training']),      ModTraining::class);
        $this->deleteRelatedItems(ModJobExperience::where('employ_id', $employee->id)->get(), collect($detail['jobExperience']), ModJobExperience::class);
        $this->deleteRelatedItems(ModFamily::where('employ_id', $employee->id)->get(),       collect($detail['family']),        ModFamily::class);
        $this->deleteRelatedItems(ModEducation::where('employ_id', $employee->id)->get(),    collect($detail['education']),     ModEducation::class);
        $this->deleteRelatedItems(ModContract::where('employ_id', $employee->id)->get(),     collect($detail['contract']),      ModContract::class);
        $this->deleteRelatedItems(ModCitizen::where('employ_id', $employee->id)->get(),      collect($detail['citizen']),       ModCitizen::class);

        $this->processEmployeeDetail($detail, $employee->id);
    }

    private function updateAggregates(Mod $employee): void
    {
        $last_contract = EmployeeContract::withTrashed()
            ->from('iq_employ_contract as c')
            ->leftJoin('iq_global_data as s', 's.id', '=', 'c.status_id')
            ->where('c.employ_id', $employee->id)
            ->select('c.*')
            ->orderByRaw("
                (UPPER(s.name) IN ('RESIGN','TERMINATE','RETIREMENT')) DESC,
                c.start_date DESC,
                c.end_date DESC,
                c.id DESC
            ")
            ->first();

        $employee->last_contract_id = $last_contract?->id;

        $last_career = EmployeeCareer::where('employ_id', $employee->id)
            ->orderByDesc('date')->orderByDesc('id')->first();
        $employee->last_career_id = $last_career?->id;
    }

    private function saveEmployee(Request $request, bool $isEdit = false)
    {
        $rules = $this->baseValidationRules();
        if ($isEdit) {
            $rules = array_merge(['id' => 'required|exists:iq_employ,id'], $rules);
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $employee = $isEdit ? Mod::find($request->input('id')) : null;

            if ($isEdit && !$employee) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
            }

            $photoId = $isEdit ? ($employee->photo_id ?? null) : null;
            if ($request->hasFile('fileInputGeneral') && $this->isUploadedFile($request->file('fileInputGeneral'))) {
                if ($photoId) FileUpload::removeFileById($photoId);
                $photoId = FileUpload::upload('fileInputGeneral', 'employee');
            }

            $payload = [
                'org_id'                        => $request->org_id,
                'division_id'                   => $request->division_id,
                'company_id'                    => $request->company_id,
                'placement_id'                  => $request->placement_id,
                'nik'                           => $request->nik,
                'nickname'                      => $request->nickname,
                'fullname'                      => $request->fullname,
                'birth_place'                   => $request->birth_place,
                'birth_date'                    => Carbon::parse($request->birth_date)->format('Y-m-d'),
                'phone'                         => $request->phone,
                'email'                         => $request->email,
                'gender_id'                     => $request->gender_id,
                'marital_id'                    => $request->marital_id,
                'religion_id'                   => $request->religion_id,
                'join_date'                     => Carbon::parse($request->join_date)->format('Y-m-d'),
                'leave_saldo'                   => $request->leave_saldo,
                'address'                       => $request->address,
                'address_city_id'               => $request->address_city_id,
                'address_province_id'           => $request->address_province_id,
                'address_permanent'             => $request->address_permanent,
                'address_permanent_city_id'     => $request->address_permanent_city_id,
                'address_permanent_province_id' => $request->address_permanent_province_id,
                'bank_id'                       => $request->bank_id,
                'bank_account'                  => $request->bank_account,
                'emergency_relation_id'         => $request->emergency_relation_id,
                'emergency_contact_name'        => $request->emergency_contact_name,
                'emergency_contact_phone'       => $request->emergency_contact_phone,
                'emergency_contact_address'     => $request->emergency_contact_address,
                'photo_id'                      => $photoId ?? null,
                'office_id'                     => $request->office_id,
                'shift_start_time'              => $request->shift_start_time,
                'shift_end_time'                => $request->shift_end_time,
            ];

            if (!$isEdit) {
                $employee = Mod::create($payload);
            } else {
                $employee->fill($payload);
            }

            $bankAlias = optional(GlobalData::find($request->bank_id))->alias;
            if (!$bankAlias) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Bank alias not found'], 404);
            }
            $employee->bank_alias = $bankAlias;

            $detail = [
                'contract'      => $request->contract      ?? [],
                'citizen'       => $request->citizen       ?? [],
                'education'     => $request->education     ?? [],
                'family'        => $request->family        ?? [],
                'jobExperience' => $request->jobExperience ?? [],
                'career'        => $request->career        ?? [],
                'training'      => $request->training      ?? [],
            ];

            if ($isEdit) {
                $this->syncDetails($employee, $detail);
            } else {
                $this->processEmployeeDetail($detail, $employee->id);
            }

            $this->updateAggregates($employee);

            $employee->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'data'    => $employee,
                'message' => $isEdit ? 'Employee updated successfully' : 'Employee created successfully'
            ], $isEdit ? 200 : 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $isEdit ? 'Error updating employee' : 'Error creating employee',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $user               = $request->user();
        $organizations      = Organization::whereNull('deleted_at')->get();
        $divisions          = Division::whereNull('deleted_at')->get();
        $companies          = Company::whereNull('deleted_at')->get();
        $placements         = Placement::whereNull('deleted_at')->get();
        $genders            = GlobalData::where('group', 'gender')->get();
        $maritals           = GlobalData::where('group', 'marital')->get();
        $religions          = GlobalData::where('group', 'religion')->get();
        $banks              = GlobalData::where('group', 'bank')->get();
        $emergency_relations = GlobalData::where('group', 'emergency_relation')->get();
        $cities             = City::all();
        $contracts          = GlobalData::where('group', 'contract')->get();
        $careers            = GlobalData::where('group', 'career')->get();

        $params = compact(
            'user',
            'organizations',
            'divisions',
            'companies',
            'placements',
            'genders',
            'maritals',
            'religions',
            'banks',
            'emergency_relations',
            'cities',
            'contracts',
            'careers'
        );

        $view = isMobile() ? '_front.employee.mobile' : '_bak.employee.main';
        return view($view, $params);
    }

    public function data(Request $request, $counter = true)
    {
        $query = Mod::with([
            'employee_requests',
            'organization',
            'organization.position',
            'division',
            'company',
            'gender',
            'marital',
            'religion',
            'bank',
            'emergency_relation',
            'photo',
            'placement',
            'address_city',
            'address_province',
            'address_permanent_city',
            'address_permanent_province',
            'last_contract',
            'last_contract.status',
            'contracts',
            'citizens',
            'educations',
            'last_career',
            'last_career.career',
            'careers',
            'families',
            'job_experiences',
            'trainings',
        ]);

        if ($request->filled('sort')) {
            $rawSort = $request->input('sort');
            $map = [
                'company'      => 'company_id',
                'organization' => 'org_id',
                'division'     => 'division_id',
                'placement'    => 'placement_id',
                'last_contract' => 'last_contract_id',
                'last_career'  => 'last_career_id',
                'address_city' => 'address_city_id',
            ];

            $normalized = $rawSort;
            if (Str::contains($rawSort, '.')) {
                $prefix = Str::before($rawSort, '.');
                if (isset($map[$prefix])) $normalized = $map[$prefix];
            } else {
                if (isset($map[$rawSort])) $normalized = $map[$rawSort];
            }

            $allowed = [
                'nik',
                'fullname',
                'nickname',
                'address',
                'email',
                'company_id',
                'org_id',
                'division_id',
                'placement_id',
                'last_contract_id',
                'last_career_id',
                'address_city_id',
            ];

            $dir = strtolower($request->input('dir', 'asc'));
            $dir = in_array($dir, ['asc', 'desc']) ? $dir : 'asc';

            if (in_array($normalized, $allowed, true)) {
                $request->merge(['sort' => $normalized, 'dir' => $dir]);
            } else {
                $request->request->remove('sort');
                $request->request->remove('dir');
            }
        }

        $terminate = ['RESIGN', 'TERMINATE', 'RETIREMENT'];
        $today     = now();

        if ((int) $request->trash === 1) {
            $query->whereNull('deleted_at')
                ->where(function ($qq) use ($terminate, $today) {
                    $qq->whereDoesntHave('last_contract')
                        ->orWhereHas('last_contract', function ($q) use ($terminate, $today) {
                            $q->whereHas('status', function ($q2) use ($terminate) {
                                $q2->whereNotIn(DB::raw('UPPER(name)'), $terminate);
                            })->where(function ($q2) use ($today) {
                                $q2->whereNull('end_date')->orWhere('end_date', '>', $today);
                            });
                        });
                });
        } elseif ((int) $request->trash === 2) {
            $query->withTrashed()
                ->where(function ($qq) use ($terminate, $today) {
                    $qq->where(function ($q) {
                        $q->whereDoesntHave('last_contract.status', function ($q2) {
                            $q2->where(DB::raw('UPPER(name)'), 'PERMANENT');
                        });
                    })->where(function ($q) use ($terminate, $today) {
                        $q->whereNotNull('deleted_at')
                            ->orWhereHas('last_contract.status', function ($q2) use ($terminate) {
                                $q2->whereIn(DB::raw('UPPER(name)'), $terminate);
                            })
                            ->orWhereHas('last_contract', function ($q2) use ($today) {
                                $q2->where('end_date', '<=', $today);
                            });
                    });
                });
        } else {
            $query->withTrashed();
        }

        $user = $request->user();
        $roleName = strtolower(optional(optional($user)->role)->name ?? '');
        $isSuperUser = in_array($roleName, ['superadmin', 'developer']);
        $userCompany = optional(optional($user)->employee)->company_id ?? optional($user)->company_id;

        if (!$isSuperUser && $userCompany) {
            $query->where('company_id', $userCompany);
        } elseif ($request->filled('company') && $request->company !== 'all' && $request->company != 0) {
            $query->where('company_id', $request->company);
        }

        if ($request->filled('organization') && $request->organization !== 'all' && $request->organization != 0) {
            $organizationIds = $this->getDescendantOrganizationIds($request->organization);
            $query->whereIn('org_id', $organizationIds);
        } elseif (!$isSuperUser) {
            $userOrg = optional(optional($user)->employee)->org_id ?? optional($user)->organization_id;
            if ($userOrg) {
                $organizationIds = $this->getDescendantOrganizationIds($userOrg);
                $query->whereIn('org_id', $organizationIds);
            }
        }
        if ($request->filled('division') && $request->division !== 'all' && $request->division != 0) {
            $query->where('division_id', $request->division);
        }
        if ($request->filled('placement') && $request->placement !== 'all' && $request->placement != 0) {
            $query->where('placement_id', $request->placement);
        }
        if ($request->filled('position') && $request->position !== 'all' && $request->position != 0) {
            $query->whereHas('organization.position', fn($q) => $q->where('id', $request->position));
        }
        if ($request->filled('contract') && $request->contract !== 'all' && $request->contract != 0) {
            $query->whereHas('last_contract', fn($q) => $q->where('status_id', $request->contract));
        }
        if ($request->filled('career') && $request->career !== 'all' && $request->career != 0) {
            $query->whereHas('last_career', fn($q) => $q->where('career_id', $request->career));
        }

        $result = Query::open($query, [
            'nickname',
            'fullname',
            'organization.id',
            'organization.name',
            'division.id',
            'division.name',
            'company.id',
            'company.name',
            'gender.id',
            'gender.name',
            'marital.id',
            'marital.name',
            'religion.id',
            'religion.name',
            'bank.id',
            'bank.name',
            'emergency_relation.id',
            'emergency_relation.name',
            'placement.id',
            'placement.name',
            'address_city.id',
            'address_city.city',
            'address_province.id',
            'address_province.province',
            'address_permanent_city.id',
            'address_permanent_city.city',
            'address_permanent_province.id',
            'address_permanent_province.province',
        ], $counter);

        return $result;
    }

    public function get(Request $request, $id = null)
    {
        return Mod::with([
            'employee_requests',
            'organization',
            'division',
            'company',
            'gender',
            'marital',
            'religion',
            'bank',
            'emergency_relation',
            'photo',
            'placement',
            'address_city',
            'address_province',
            'address_permanent_city',
            'address_permanent_province',
            'contracts',
            'contracts.status',
            'contracts.file',
            'last_contract',
            'last_contract.status',
            'citizens',
            'citizens.citizen',
            'citizens.file',
            'educations',
            'educations.education',
            'educations.major',
            'educations.file',
            'careers',
            'careers.career',
            'careers.organization',
            'careers.placement',
            'careers.file',
            'last_career',
            'last_career.career',
            'families',
            'families.occupation',
            'families.relation',
            'job_experiences',
            'trainings',
            'trainings.file',
        ])->where('id', $id)->first();
    }

    public function view(Request $request, $id = null)
    {
        if ($data = Mod::with([
            'address_city',
            'address_province',
            'address_permanent_city',
            'address_permanent_province',
            'last_career.career',
            'last_career.placement',
        ])->find($id)) {
            $user   = $request->user();
            $params = ['user' => $user, 'data' => $data];
            return view('_bak.employee.detail', $params);
        }

        abort('404');
    }

    public function create(Request $request)
    {
        return $this->saveEmployee($request, false);
    }

    public function edit(Request $request)
    {
        return $this->saveEmployee($request, true);
    }

    public function exportExcel(Request $request)
    {
        ini_set('memory_limit', '64048M');
        ini_set('max_execution_time', '300');

        $title = [];
        $title[] = ['Employee', 'h2'];

        if ($request->input('trash') !== null && $request->input('trash') !== 'null') {
            if ($request->input('trash') == 1)     $title[] = ['DATA : Active', 'h5'];
            elseif ($request->input('trash') == 2) $title[] = ['DATA : Deleted', 'h5'];
        }
        if ($request->filled('company') && $request->company !== 'all' && $request->company != 0) {
            if ($client = Company::find($request->input('company')))
                $title[] = ['COMPANY : ' . $client->name, 'h5'];
        }
        if ($request->filled('organization') && $request->organization !== 'all' && $request->organization != 0) {
            if ($client = Organization::find($request->input('organization')))
                $title[] = ['ORGANIZATION : ' . $client->name, 'h5'];
        }
        if ($request->filled('division') && $request->division !== 'all' && $request->division != 0) {
            if ($client = Division::find($request->input('division')))
                $title[] = ['DIVISION : ' . $client->name, 'h5'];
        }
        if ($request->filled('placement') && $request->placement !== 'all' && $request->placement != 0) {
            if ($client = Placement::find($request->input('placement')))
                $title[] = ['PLACEMENT : ' . $client->name, 'h5'];
        }
        if ($request->filled('position') && $request->position !== 'all' && $request->position != 0) {
            if ($client = GlobalData::where('group', 'position')->find($request->input('position')))
                $title[] = ['POSITION : ' . $client->name, 'h5'];
        }
        if ($request->filled('contract') && $request->contract !== 'all' && $request->contract != 0) {
            if ($client = GlobalData::where('group', 'contract_status')->find($request->input('contract')))
                $title[] = ['CONTRACT : ' . $client->name, 'h5'];
        }
        if ($request->filled('career') && $request->career !== 'all' && $request->career != 0) {
            if ($client = GlobalData::where('group', 'career')->find($request->input('career')))
                $title[] = ['CAREER : ' . $client->name, 'h5'];
        }

        $data = $this->data($request, false);

        $columns = [
            [
                'text'    => 'GENERAL',
                'columns' => [
                    [
                        'text'      => 'STATUS',
                        'dataIndex' => 'last_contract',
                        'width'     => 120,
                        'renderer'  => function ($contract) {
                            if (!$contract || !$contract->status) return 'INACTIVE';
                            $status  = strtoupper($contract->status->name);
                            $endDate = $contract->end_date ? Carbon::parse($contract->end_date) : null;
                            $today   = Carbon::today();
                            if (in_array($status, ['RESIGN', 'TERMINATE', 'RETIREMENT'])) return 'INACTIVE';
                            if (in_array($status, ['PERMANENT', 'FREELANCE'])) return 'ACTIVE';
                            if ($endDate) return $endDate->greaterThanOrEqualTo($today) ? 'ACTIVE' : 'INACTIVE';
                            return 'INACTIVE';
                        }
                    ],
                    ['text' => 'NIK',       'dataIndex' => 'nik',      'width' => 150, 'align' => 'center'],
                    ['text' => 'NICKNAME',  'dataIndex' => 'nickname', 'width' => 150],
                    ['text' => 'FULL NAME', 'dataIndex' => 'fullname', 'width' => 200],
                    ['text' => 'COMPANY',      'dataIndex' => 'company',      'width' => 150, 'renderer' => fn($e) => $e?->name ?? '-'],
                    ['text' => 'ORGANIZATION', 'dataIndex' => 'organization', 'width' => 200, 'renderer' => fn($e) => $e?->name ?? '-'],
                    ['text' => 'DIVISION',     'dataIndex' => 'division',     'width' => 150, 'renderer' => fn($e) => $e?->name ?? '-'],
                    ['text' => 'PLACEMENT',    'dataIndex' => 'placement',    'width' => 150, 'renderer' => fn($e) => $e?->name ?? '-'],
                    ['text' => 'CONTRACT STATUS', 'dataIndex' => 'last_contract', 'width' => 150, 'renderer' => fn($e) => $e?->status?->name ?? '-'],
                    ['text' => 'CAREER',       'dataIndex' => 'last_career',  'width' => 150, 'renderer' => fn($e) => $e?->career?->name ?? '-'],
                    ['text' => 'BIRTH PLACE',  'dataIndex' => 'birth_place',  'width' => 150],
                    ['text' => 'BIRTH DATE',   'dataIndex' => 'birth_date',   'width' => 120, 'type' => 'date', 'align' => 'center'],
                    ['text' => 'PHONE',        'dataIndex' => 'phone',        'width' => 150, 'align' => 'center'],
                    ['text' => 'EMAIL',        'dataIndex' => 'email',        'width' => 200],
                    ['text' => 'GENDER',   'dataIndex' => 'gender',   'width' => 100, 'renderer' => fn($e) => $e?->name ?? '-'],
                    ['text' => 'MARITAL',  'dataIndex' => 'marital',  'width' => 100, 'renderer' => fn($e) => $e?->name ?? '-'],
                    ['text' => 'RELIGION', 'dataIndex' => 'religion', 'width' => 100, 'renderer' => fn($e) => $e?->name ?? '-'],
                    ['text' => 'JOIN DATE',          'dataIndex' => 'join_date',      'width' => 120, 'type' => 'date', 'align' => 'center'],
                    ['text' => 'CONTRACT END DATE',  'dataIndex' => 'last_contract',  'width' => 140, 'type' => 'date', 'align' => 'center', 'renderer' => fn($e) => $e?->end_date ?? null],
                    ['text' => 'LEAVE SALDO',        'dataIndex' => 'leave_saldo',    'width' => 100, 'type' => 'int', 'align' => 'center'],
                    ['text' => 'ADDRESS',            'dataIndex' => 'address',        'width' => 300],
                    ['text' => 'CITY',               'dataIndex' => 'address_city',   'width' => 150, 'renderer' => fn($e) => $e?->city ?? '-'],
                    ['text' => 'PROVINCE',           'dataIndex' => 'address_province', 'width' => 150, 'renderer' => fn($e) => $e?->province ?? '-'],
                    ['text' => 'PERMANENT ADDRESS',  'dataIndex' => 'address_permanent',           'width' => 300, 'renderer' => fn($e) => $e ?? '-'],
                    ['text' => 'PERMANENT CITY',     'dataIndex' => 'address_permanent_city',      'width' => 150, 'renderer' => fn($e) => $e?->city ?? '-'],
                    ['text' => 'PERMANENT PROVINCE', 'dataIndex' => 'address_permanent_province',  'width' => 150, 'renderer' => fn($e) => $e?->province ?? '-'],
                    ['text' => 'BANK',         'dataIndex' => 'bank', 'width' => 200, 'renderer' => fn($e) => $e?->name ?? '-'],
                    ['text' => 'BANK ALIAS',   'dataIndex' => 'bank', 'width' => 150, 'renderer' => fn($e) => $e?->alias ?? '-'],
                    ['text' => 'BANK ACCOUNT', 'dataIndex' => 'bank_account', 'width' => 180],
                    ['text' => 'EMERGENCY RELATION', 'dataIndex' => 'emergency_relation',      'width' => 160, 'renderer' => fn($e) => $e?->name ?? '-'],
                    ['text' => 'EMERGENCY NAME',     'dataIndex' => 'emergency_contact_name',  'width' => 200],
                    ['text' => 'EMERGENCY PHONE',    'dataIndex' => 'emergency_contact_phone', 'width' => 160, 'align' => 'center'],
                    ['text' => 'EMERGENCY ADDRESS',  'dataIndex' => 'emergency_contact_address', 'width' => 250],
                ]
            ],
            [
                'text'    => 'CONTRACT',
                'columns' => [
                    ['text' => 'STATUS',      'dataIndex' => 'contracts', 'width' => 150, 'renderer' => fn($items) => $items->map(fn($c) => $c->status?->name ?? '-')->implode(' | ')],
                    ['text' => 'START DATE',  'dataIndex' => 'contracts', 'width' => 120, 'renderer' => fn($items) => $items->map(fn($c) => $c->start_date ? Carbon::parse($c->start_date)->format('d/m/Y') : '-')->implode(' | ')],
                    ['text' => 'END DATE',    'dataIndex' => 'contracts', 'width' => 120, 'renderer' => fn($items) => $items->map(fn($c) => $c->end_date ? Carbon::parse($c->end_date)->format('d/m/Y') : '-')->implode(' | ')],
                    ['text' => 'DESCRIPTION', 'dataIndex' => 'contracts', 'width' => 200, 'renderer' => fn($items) => $items->map(fn($c) => $c->description ?? '-')->implode(' | ')],
                ]
            ],
            [
                'text'    => 'CITIZEN',
                'columns' => [
                    ['text' => 'TYPE',        'dataIndex' => 'citizens', 'width' => 150, 'renderer' => fn($items) => $items->map(fn($c) => $c->citizen?->name ?? '-')->implode(' | ')],
                    ['text' => 'VALUE',       'dataIndex' => 'citizens', 'width' => 200, 'renderer' => fn($items) => $items->map(fn($c) => $c->value ?? '-')->implode(' | ')],
                    ['text' => 'DESCRIPTION', 'dataIndex' => 'citizens', 'width' => 200, 'renderer' => fn($items) => $items->map(fn($c) => $c->description ?? '-')->implode(' | ')],
                ]
            ],
            [
                'text'    => 'EDUCATION',
                'columns' => [
                    ['text' => 'LEVEL',       'dataIndex' => 'educations', 'width' => 120, 'renderer' => fn($items) => $items->map(fn($e) => $e->education?->name ?? '-')->implode(' | ')],
                    ['text' => 'MAJOR',       'dataIndex' => 'educations', 'width' => 150, 'renderer' => fn($items) => $items->map(fn($e) => $e->major?->name ?? '-')->implode(' | ')],
                    ['text' => 'INSTITUTION', 'dataIndex' => 'educations', 'width' => 200, 'renderer' => fn($items) => $items->map(fn($e) => $e->institution ?? '-')->implode(' | ')],
                    ['text' => 'GRADUATE',    'dataIndex' => 'educations', 'width' => 120, 'renderer' => fn($items) => $items->map(fn($e) => $e->graduate ? Carbon::parse($e->graduate)->format('d/m/Y') : '-')->implode(' | ')],
                    ['text' => 'IPK',         'dataIndex' => 'educations', 'width' => 80,  'align' => 'center', 'renderer' => fn($items) => $items->map(fn($e) => $e->ipk ?? '-')->implode(' | ')],
                    ['text' => 'DESCRIPTION', 'dataIndex' => 'educations', 'width' => 200, 'renderer' => fn($items) => $items->map(fn($e) => $e->description ?? '-')->implode(' | ')],
                ]
            ],
            [
                'text'    => 'FAMILY',
                'columns' => [
                    ['text' => 'NAME',             'dataIndex' => 'families', 'width' => 180, 'renderer' => fn($items) => $items->map(fn($f) => $f->name ?? '-')->implode(' | ')],
                    ['text' => 'NIK',              'dataIndex' => 'families', 'width' => 150, 'align' => 'center', 'renderer' => fn($items) => $items->map(fn($f) => $f->nik ?? '-')->implode(' | ')],
                    ['text' => 'BIRTH DATE',       'dataIndex' => 'families', 'width' => 120, 'renderer' => fn($items) => $items->map(fn($f) => $f->birth_date ? Carbon::parse($f->birth_date)->format('d/m/Y') : '-')->implode(' | ')],
                    ['text' => 'RELATION',         'dataIndex' => 'families', 'width' => 120, 'renderer' => fn($items) => $items->map(fn($f) => $f->relation?->name ?? '-')->implode(' | ')],
                    ['text' => 'OCCUPATION',       'dataIndex' => 'families', 'width' => 150, 'renderer' => fn($items) => $items->map(fn($f) => $f->occupation?->name ?? '-')->implode(' | ')],
                    ['text' => 'OCC. DESCRIPTION', 'dataIndex' => 'families', 'width' => 180, 'renderer' => fn($items) => $items->map(fn($f) => $f->occupation_description ?? '-')->implode(' | ')],
                    ['text' => 'ADDRESS',          'dataIndex' => 'families', 'width' => 200, 'renderer' => fn($items) => $items->map(fn($f) => $f->address ?? '-')->implode(' | ')],
                    ['text' => 'PHONE',            'dataIndex' => 'families', 'width' => 130, 'align' => 'center', 'renderer' => fn($items) => $items->map(fn($f) => $f->phone ?? '-')->implode(' | ')],
                ]
            ],
            [
                'text'    => 'JOB EXPERIENCE',
                'columns' => [
                    ['text' => 'COMPANY',         'dataIndex' => 'job_experiences', 'width' => 200, 'renderer' => fn($items) => $items->map(fn($j) => $j->name ?? '-')->implode(' | ')],
                    ['text' => 'JOB TITLE',       'dataIndex' => 'job_experiences', 'width' => 180, 'renderer' => fn($items) => $items->map(fn($j) => $j->job_title ?? '-')->implode(' | ')],
                    ['text' => 'START DATE',      'dataIndex' => 'job_experiences', 'width' => 120, 'renderer' => fn($items) => $items->map(fn($j) => $j->start_date ? Carbon::parse($j->start_date)->format('d/m/Y') : '-')->implode(' | ')],
                    ['text' => 'END DATE',        'dataIndex' => 'job_experiences', 'width' => 120, 'renderer' => fn($items) => $items->map(fn($j) => $j->end_date ? Carbon::parse($j->end_date)->format('d/m/Y') : '-')->implode(' | ')],
                    ['text' => 'SALARY',          'dataIndex' => 'job_experiences', 'width' => 130, 'renderer' => fn($items) => $items->map(fn($j) => $j->salary ? number_format($j->salary, 0, ',', '.') : '-')->implode(' | ')],
                    ['text' => 'JOB DESCRIPTION', 'dataIndex' => 'job_experiences', 'width' => 200, 'renderer' => fn($items) => $items->map(fn($j) => $j->job_description ?? '-')->implode(' | ')],
                    ['text' => 'REASON LEAVING',  'dataIndex' => 'job_experiences', 'width' => 200, 'renderer' => fn($items) => $items->map(fn($j) => $j->reason_leaving ?? '-')->implode(' | ')],
                ]
            ],
            [
                'text'    => 'CAREER',
                'columns' => [
                    ['text' => 'CAREER',       'dataIndex' => 'careers', 'width' => 150, 'renderer' => fn($items) => $items->map(fn($c) => $c->career?->name ?? '-')->implode(' | ')],
                    ['text' => 'PLACEMENT',    'dataIndex' => 'careers', 'width' => 150, 'renderer' => fn($items) => $items->map(fn($c) => $c->placement?->name ?? '-')->implode(' | ')],
                    ['text' => 'DATE',         'dataIndex' => 'careers', 'width' => 120, 'renderer' => fn($items) => $items->map(fn($c) => $c->date ? Carbon::parse($c->date)->format('d/m/Y') : '-')->implode(' | ')],
                    ['text' => 'ORGANIZATION', 'dataIndex' => 'careers', 'width' => 200, 'renderer' => fn($items) => $items->map(fn($c) => $c->organization?->name ?? '-')->implode(' | ')],
                    ['text' => 'DESCRIPTION',  'dataIndex' => 'careers', 'width' => 200, 'renderer' => fn($items) => $items->map(fn($c) => $c->description ?? '-')->implode(' | ')],
                ]
            ],
            [
                'text'    => 'TRAINING',
                'columns' => [
                    ['text' => 'TITLE',         'dataIndex' => 'trainings', 'width' => 200, 'renderer' => fn($items) => $items->map(fn($t) => $t->title ?? '-')->implode(' | ')],
                    ['text' => 'LOCATION',      'dataIndex' => 'trainings', 'width' => 150, 'renderer' => fn($items) => $items->map(fn($t) => $t->location ?? '-')->implode(' | ')],
                    ['text' => 'START DATE',    'dataIndex' => 'trainings', 'width' => 120, 'renderer' => fn($items) => $items->map(fn($t) => $t->start_date ? Carbon::parse($t->start_date)->format('d/m/Y') : '-')->implode(' | ')],
                    ['text' => 'END DATE',      'dataIndex' => 'trainings', 'width' => 120, 'renderer' => fn($items) => $items->map(fn($t) => $t->end_date ? Carbon::parse($t->end_date)->format('d/m/Y') : '-')->implode(' | ')],
                    ['text' => 'DESCRIPTION',   'dataIndex' => 'trainings', 'width' => 200, 'renderer' => fn($items) => $items->map(fn($t) => $t->description ?? '-')->implode(' | ')],
                    ['text' => 'INTERNAL',      'dataIndex' => 'trainings', 'width' => 100, 'align' => 'center', 'renderer' => fn($items) => $items->map(fn($t) => $t->is_internal ? 'Yes' : 'No')->implode(' | ')],
                    ['text' => 'CERTIFICATION', 'dataIndex' => 'trainings', 'width' => 110, 'align' => 'center', 'renderer' => fn($items) => $items->map(fn($t) => $t->is_certification ? 'Yes' : 'No')->implode(' | ')],
                ]
            ],
        ];

        $params = [
            'title'    => $title,
            'columns'  => $columns,
            'data'     => $data,
            'filename' => 'Employee-' . date('YmdHi'),
            'footer'   => [config('app.name') . ' (' . date('d F Y H:i:s') . ')'],
        ];

        return ExportExcel::export($params);
    }

    public function exportPdf(Request $request, $id)
    {
        try {
            $user = auth()->user();
            $view = 'reports.cv_pdf';

            $rec = Mod::with($this->pdfRelations())->find($id);

            if (!$rec) {
                return response()->json(['success' => false, 'message' => 'Data not found'], 404);
            }

            $fileName = strtolower(
                preg_replace('/[^a-z0-9_]/', '', preg_replace('/\s+/', '_', $rec->fullname))
            );

            $photoUrl = null;
            if ($rec->photo_id && $rec->photo) {
                $photoUrl = public_path('/storage/uploads/' . $rec->photo->filename);
            }

            $params = ['user' => $user, 'data' => $rec, 'photoUrl' => $photoUrl];
            $html   = view($view, $params)->render();
            $pdf    = Pdf::loadHtml($html);

            return $pdf->download('Employee_Data_' . $fileName . '.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error exporting PDF',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function exportPdfMultiple(Request $request)
    {
        $data = json_decode($request->data);

        if (!$data || !is_array($data) || count($data) === 0) {
            return response()->json(['success' => false, 'message' => 'No data provided or invalid format.'], 400);
        }

        try {
            $zip         = new ZipArchive();
            $zipFileName = 'Employee_Reports_' . time() . '.zip';
            $tempDir     = storage_path('app/public/temp');
            $tempPath    = $tempDir . '/' . $zipFileName;

            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0775, true);
            }

            if ($zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                return response()->json(['error' => 'Could not create ZIP file.'], 500);
            }

            $pdfTempFiles = [];

            foreach ($data as $id) {
                $record = Mod::with($this->pdfRelations())->find($id);

                if (!$record) continue;

                $user     = auth()->user();
                $photoUrl = null;

                if ($record->photo_id && $record->photo) {
                    $photoUrl = public_path('/storage/uploads/' . $record->photo->filename);
                }

                $params     = ['user' => $user, 'data' => $record, 'photoUrl' => $photoUrl];
                $html       = view('reports.cv_pdf', $params)->render();
                $pdf        = Pdf::loadHtml($html);
                $pdfContent = $pdf->output();

                $filename    = strtolower(
                    preg_replace('/[^a-z0-9_]/', '', preg_replace('/\s+/', '_', $record->fullname))
                );
                $pdfFileName = 'Employee_Data_' . $filename . '.pdf';
                $pdfTempPath = $tempDir . '/' . $pdfFileName;

                file_put_contents($pdfTempPath, $pdfContent);

                $zip->addFile($pdfTempPath, $pdfFileName);
                $pdfTempFiles[] = $pdfTempPath;
            }

            $zip->close();

            foreach ($pdfTempFiles as $tmpFile) {
                if (file_exists($tmpFile)) {
                    @unlink($tmpFile);
                }
            }

            return response()->json([
                'url' => route('employee.downloadZip', ['filename' => $zipFileName])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating ZIP',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function downloadZip($filename)
    {
        $filePath = storage_path('app/public/temp/' . $filename);
        if (file_exists($filePath)) {
            return response()->download($filePath)->deleteFileAfterSend(true);
        }
        return abort(404);
    }

    public function importFormat(Request $request)
    {
        return Excel::download(new Format(), 'employee_format.xlsx');
    }

    public function importFormatContract(Request $request)
    {
        return Excel::download(new FormatContract(), 'employee_format_contract.xlsx');
    }

    public function importFormatCareer(Request $request)
    {
        return Excel::download(new FormatCareer(), 'employee_format_career.xlsx');
    }

    public function importFormatTraining(Request $request)
    {
        return Excel::download(new FormatTraining(), 'employee_format_training.xlsx');
    }

    public function importFormatJobExperience(Request $request)
    {
        return Excel::download(new FormatJobExperience(), 'employee_format_job_experience.xlsx');
    }

    public function importFormatFamily(Request $request)
    {
        return Excel::download(new FormatFamily(), 'employee_format_family.xlsx');
    }

    public function importFormatEducation(Request $request)
    {
        return Excel::download(new FormatEducation(), 'employee_format_education.xlsx');
    }

    public function importFormatCitizen(Request $request)
    {
        return Excel::download(new FormatCitizen(), 'employee_format_citizen.xlsx');
    }

    public function importData(Request $request)
    {
        if ($upload = FileUpload::upload('file', 'employee-import')) {
            $user      = $request->user();
            $file      = Upload::find($upload);
            $fileexcel = storage_path('app/public/uploads/' . $file->filename);

            $importExcel = new Import($user);
            Excel::import($importExcel, $fileexcel);

            unlink($fileexcel);
            Upload::where('id', $upload)->delete();

            return ['success' => true, 'message' => $importExcel->logs()];
        }

        return ['success' => false, 'message' => 'The data you uploaded was not found'];
    }

    public function importDataContract(Request $request)
    {
        return $this->processImport($request, 'employee-import-contract', ImportContract::class);
    }

    public function importDataCareer(Request $request)
    {
        return $this->processImport($request, 'employee-import-career', ImportCareer::class);
    }

    public function importDataTraining(Request $request)
    {
        return $this->processImport($request, 'employee-import-training', ImportTraining::class);
    }

    public function importDataJobExperience(Request $request)
    {
        return $this->processImport($request, 'employee-import-job-experience', ImportJobExperience::class);
    }

    public function importDataFamily(Request $request)
    {
        return $this->processImport($request, 'employee-import-family', ImportFamily::class);
    }

    public function importDataEducation(Request $request)
    {
        return $this->processImport($request, 'employee-import-education', ImportEducation::class);
    }

    public function importDataCitizen(Request $request)
    {
        return $this->processImport($request, 'employee-import-citizen', ImportCitizen::class);
    }

    public function delete(Request $request)
    {
        try {
            if ($data = json_decode($request->data)) {
                DB::beginTransaction();
                foreach ($data as $id) {
                    if ($rec = Mod::find($id)) $rec->delete();
                }
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Success deleting employees']);
            }
            return response()->json(['success' => false, 'message' => 'No Data!'], 400);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error deleting employee', 'error' => $e->getMessage()], 500);
        }
    }

    public function restore(Request $request)
    {
        try {
            if ($data = json_decode($request->data)) {
                DB::beginTransaction();
                foreach ($data as $id) {
                    if ($rec = Mod::withTrashed()->find($id)) $rec->restore();
                }
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Success restoring employees']);
            }
            return response()->json(['success' => false, 'message' => 'No Data!'], 400);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error restoring employees', 'error' => $e->getMessage()], 500);
        }
    }

    public function forcedelete(Request $request)
    {
        try {
            if ($data = json_decode($request->data)) {
                DB::beginTransaction();

                foreach ($data as $id) {
                    $rec = Mod::withTrashed()->find($id);
                    if (!$rec) continue;

                    if ($rec->photo_id) FileUpload::removeFileById($rec->photo_id);

                    foreach ($rec->contracts as $contract) {
                        if ($contract->file_id) FileUpload::removeFileById($contract->file_id);
                        $contract->forceDelete();
                    }
                    foreach ($rec->citizens as $citizen) {
                        if ($citizen->file_id) FileUpload::removeFileById($citizen->file_id);
                        DB::table('iq_employ_citizen')
                            ->where('employ_id', $citizen->employ_id)
                            ->where('citizen_id', $citizen->citizen_id)
                            ->delete();
                    }
                    foreach ($rec->educations as $education) {
                        if ($education->file_id) FileUpload::removeFileById($education->file_id);
                        $education->forceDelete();
                    }
                    foreach ($rec->careers as $career) {
                        if ($career->file_id) FileUpload::removeFileById($career->file_id);
                        $career->forceDelete();
                    }
                    foreach ($rec->trainings as $training) {
                        if ($training->file_id) FileUpload::removeFileById($training->file_id);
                        $training->forceDelete();
                    }
                    foreach ($rec->job_experiences as $job_experience) {
                        if ($job_experience->file_id) FileUpload::removeFileById($job_experience->file_id);
                        $job_experience->forceDelete();
                    }
                    foreach ($rec->families as $family) {
                        if (property_exists($family, 'file_id') && $family->file_id) {
                            FileUpload::removeFileById($family->file_id);
                        }
                        $family->forceDelete();
                    }

                    $rec->forceDelete();
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Success permanently deleted employees']);
            }

            return response()->json(['success' => false, 'message' => 'No Data!'], 400);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error permanently deleting employees', 'error' => $e->getMessage()], 500);
        }
    }

    protected function getDescendantOrganizationIds($organizationId)
    {
        $descendantIds = [$organizationId];

        $fetchChildren = function ($parentId) use (&$descendantIds, &$fetchChildren) {
            $children = Organization::where('parent_id', $parentId)->pluck('id');
            foreach ($children as $childId) {
                $descendantIds[] = $childId;
                $fetchChildren($childId);
            }
        };

        $fetchChildren($organizationId);
        return $descendantIds;
    }

    protected function processImport(Request $request, $uploadFolder, $importClass)
    {
        if ($upload = FileUpload::upload('file', $uploadFolder)) {
            $user      = $request->user();
            $file      = Upload::find($upload);
            $fileexcel = storage_path('app/public/uploads/' . $file->filename);

            $importExcel = new $importClass($user);
            Excel::import($importExcel, $fileexcel);

            unlink($fileexcel);
            Upload::where('id', $upload)->delete();

            $logs = $importExcel->logs();

            if ($logs['totalError'] > 0) {
                return response()->json([
                    'error'      => true,
                    'message'    => 'There was an error while parsing the file.',
                    'errorLog'   => $logs['errorLog'],
                    'totalRow'   => $logs['totalRow'],
                    'totalError' => $logs['totalError'],
                ]);
            }

            return response()->json([
                'success'  => true,
                'data'     => $importExcel->getData(),
                'message'  => [
                    'totalRow'     => $logs['totalRow'],
                    'totalSuccess' => $logs['totalSuccess'],
                    'totalError'   => $logs['totalError'],
                ],
            ]);
        }

        return response()->json([
            'error'   => true,
            'message' => 'The data you uploaded was not found',
        ], 400);
    }
}
