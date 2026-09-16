<?php

namespace App\Controllers\Front\Employees;

use App\Http\Controllers\Controller;
use App\Libraries\FileUpload;
use App\Libraries\Query;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Employees\Employee as ModEmployee;
use App\Models\Employees\EmployeeRequest as ModEmployeeRequest;
use App\Models\GlobalData;
use App\SystemModels\Globals\Upload;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class EmployeeRequest extends Controller
{
    public function data(Request $request, $employId, $counter = true)
    {
        $query = ModEmployeeRequest::with(['organization', 'organization.position', 'division', 'company', 'gender', 'marital', 'religion', 'bank', 'emergency_relation', 'photo', 'placement', 'address_city', 'address_province', 'address_permanent_city', 'address_permanent_province', 'approved_by', 'employ']);

        $query->orderBy('created_at', 'DESC');

        if (!in_array($request->user()->role->name, ['HRGA', 'SUPERADMIN', 'DEVELOPER'])) {
            $query->where('employ_id', $employId);
        }

        if (!$request->trash) {
            $query->withTrashed();
        }
        if ($request->trash == 2) {
            $query->onlyTrashed();
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
            'photo.id',
            'photo.name',
            'placement.id',
            'placement.name',
            'address_city.id',
            'address_city.name',
            'address_province.id',
            'address_province.name',
            'address_permanent_city.id',
            'address_permanent_city.name',
            'address_permanent_province.id',
            'address_permanent_province.name'
        ], $counter);

        return $result;
    }

    public function get(Request $request, $id = null)
    {
        return ModEmployeeRequest::with(['organization', 'organization.position', 'division', 'company', 'gender', 'marital', 'religion', 'bank', 'emergency_relation', 'photo', 'placement', 'address_city', 'address_province', 'address_permanent_city', 'address_permanent_province', 'approved_by', 'employ'])->where('id', $id)->first();
    }

    public function getByEmployeeId(Request $request, $id = null)
    {
        return ModEmployeeRequest::with(['organization', 'organization.position', 'division', 'company', 'gender', 'marital', 'religion', 'bank', 'emergency_relation', 'photo', 'placement', 'address_city', 'address_province', 'address_permanent_city', 'address_permanent_province', 'approved_by', 'employ'])->where('employ_id', $id)->where('approved_status', null)->first();
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'employ_id' => 'required|exists:iq_employ,id',
                'org_id' => 'nullable|integer|exists:iq_org,id',
                'division_id' => 'nullable|integer|exists:iq_division,id',
                'company_id' => 'nullable|integer|exists:iq_company,id',
                'placement_id' => 'nullable|integer|exists:iq_placement,id',
                'nik' => 'nullable|string',
                'nickname' => 'nullable|string',
                'fullname' => 'nullable|string',
                'birth_place' => 'nullable|string',
                'birth_date' => 'nullable',
                'phone' => 'nullable|string',
                'email' => 'nullable|string',
                'gender_id' => 'nullable|integer|exists:iq_global_data,id',
                'marital_id' => 'nullable|integer|exists:iq_global_data,id',
                'religion_id' => 'nullable|integer|exists:iq_global_data,id',
                'join_date' => 'nullable',
                'leave_saldo' => 'nullable|numeric',
                'address' => 'nullable|string',
                'address_city_id' => 'nullable|integer|exists:iq_city,id',
                'address_province_id' => 'nullable|integer|exists:iq_city,id',
                'address_permanent' => 'nullable|string',
                'address_permanent_city_id' => 'nullable|integer|exists:iq_city,id',
                'address_permanent_province_id' => 'nullable|integer|exists:iq_city,id',
                'bank_id' => 'nullable|integer|exists:iq_global_data,id',
                'bank_account' => 'nullable|string',
                'emergency_relation_id' => 'nullable|integer|exists:iq_global_data,id',
                'emergency_contact_name' => 'nullable|string',
                'emergency_contact_phone' => 'nullable|string',
                'emergency_contact_address' => 'nullable|string',
                'fileInputGeneral' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $currentEmploy = ModEmployee::find($request->employ_id);
            if (!$currentEmploy) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }

            DB::beginTransaction();

            $photo = $currentEmploy->photo_id ?? null;
            if ($request->hasFile('fileInputGeneral')) {
                $photo = FileUpload::upload('fileInputGeneral', 'employee-request');
            }

            $fields = [
                'org_id',
                'division_id',
                'company_id',
                'placement_id',
                'nik',
                'nickname',
                'fullname',
                'birth_place',
                'birth_date',
                'phone',
                'email',
                'gender_id',
                'marital_id',
                'religion_id',
                'join_date',
                'leave_saldo',
                'address',
                'address_city_id',
                'address_province_id',
                'address_permanent',
                'address_permanent_city_id',
                'address_permanent_province_id',
                'bank_id',
                'bank_account',
                'emergency_relation_id',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_address',
            ];

            $data = [
                'employ_id' => $request->employ_id,
                'photo_id'  => $photo,
            ];

            foreach ($fields as $field) {
                if ($request->filled($field)) {
                    $val = $request->input($field);
                    if (in_array($field, ['birth_date', 'join_date']) && !empty($val)) {
                        try {
                            $val = Carbon::parse($val)->format('Y-m-d');
                        } catch (\Exception $e) {
                        }
                    }
                    $data[$field] = $val;
                } else {
                    $val = $currentEmploy->$field;
                    if (in_array($field, ['birth_date', 'join_date']) && !empty($val)) {
                        try {
                            $val = Carbon::parse($val)->format('Y-m-d');
                        } catch (\Exception $e) {
                        }
                    }
                    $data[$field] = $val;
                }
            }

            if (!empty($data['bank_id'])) {
                $bank_alias = GlobalData::find($data['bank_id'])?->alias ?? null;
                $data['bank_alias'] = $bank_alias;
            }

            $employee = ModEmployeeRequest::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $employee,
                'message' => 'Request successfully created'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error creating employee request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:iq_employ_request,id',
                'employ_id' => 'nullable|exists:iq_employ,id',
                'org_id' => 'nullable|integer|exists:iq_org,id',
                'division_id' => 'nullable|integer|exists:iq_division,id',
                'company_id' => 'nullable|integer|exists:iq_company,id',
                'placement_id' => 'nullable|integer|exists:iq_placement,id',
                'nik' => 'nullable|string',
                'nickname' => 'nullable|string',
                'fullname' => 'nullable|string',
                'birth_place' => 'nullable|string',
                'birth_date' => 'nullable',
                'phone' => 'nullable|string',
                'email' => 'nullable|string',
                'gender_id' => 'nullable|integer|exists:iq_global_data,id',
                'marital_id' => 'nullable|integer|exists:iq_global_data,id',
                'religion_id' => 'nullable|integer|exists:iq_global_data,id',
                'join_date' => 'nullable',
                'leave_saldo' => 'nullable|numeric',
                'address' => 'nullable|string',
                'address_city_id' => 'nullable|integer|exists:iq_city,id',
                'address_province_id' => 'nullable|integer|exists:iq_city,id',
                'address_permanent' => 'nullable|string',
                'address_permanent_city_id' => 'nullable|integer|exists:iq_city,id',
                'address_permanent_province_id' => 'nullable|integer|exists:iq_city,id',
                'bank_id' => 'nullable|integer|exists:iq_global_data,id',
                'bank_account' => 'nullable|string',
                'emergency_relation_id' => 'nullable|integer|exists:iq_global_data,id',
                'emergency_contact_name' => 'nullable|string',
                'emergency_contact_phone' => 'nullable|string',
                'emergency_contact_address' => 'nullable|string',
                'fileInputGeneral' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $employee = ModEmployeeRequest::find($request->input('id'));

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee request not found'
                ], 404);
            }

            $fields = [
                'employ_id',
                'org_id',
                'division_id',
                'company_id',
                'placement_id',
                'nik',
                'nickname',
                'fullname',
                'birth_place',
                'birth_date',
                'phone',
                'email',
                'gender_id',
                'marital_id',
                'religion_id',
                'join_date',
                'leave_saldo',
                'address',
                'address_city_id',
                'address_province_id',
                'address_permanent',
                'address_permanent_city_id',
                'address_permanent_province_id',
                'bank_id',
                'bank_account',
                'emergency_relation_id',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_address',
            ];

            foreach ($fields as $field) {
                if ($request->filled($field)) {
                    $val = $request->input($field);
                    if (in_array($field, ['birth_date', 'join_date']) && !empty($val)) {
                        try {
                            $val = Carbon::parse($val)->format('Y-m-d');
                        } catch (\Exception $e) {
                        }
                    }
                    $employee->$field = $val;
                }
            }

            if ($request->filled('bank_id')) {
                $bankAlias = GlobalData::find($request->input('bank_id'))?->alias ?? null;
                if ($bankAlias) {
                    $employee->bank_alias = $bankAlias;
                }
            }

            if ($request->hasFile('fileInputGeneral')) {
                if ($employee->photo_id) {
                    FileUpload::removeFileById($employee->photo_id);
                }
                $photo = FileUpload::upload('fileInputGeneral', 'employee-request');
                $employee->photo_id = $photo ?? null;
            }

            $employee->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $employee,
                'message' => 'Request successfully updated'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error updating employee request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function approve(Request $request)
    {
        if (!in_array($request->user()->role->name, ['HRGA', 'SUPERADMIN', 'DEVELOPER'])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menyetujui/menolak pengajuan ini.'
            ], 403);
        }

        try {
            $id = $request->input('id');
            $note = $request->input('note');

            if ($id) {
                DB::beginTransaction();

                $rec = ModEmployeeRequest::find($id);
                if ($rec) {
                    $rec->approved_status = 1;
                    $rec->approved_at = now();
                    $rec->approved_by = $request->user()->id;
                    $rec->approved_note = $note;
                    $rec->save();

                    $recEmployee = ModEmployee::find($rec->employ_id);
                    if ($recEmployee) {
                        $updateData = [
                            'org_id' => $rec->org_id ?? $recEmployee->org_id,
                            'division_id' => $rec->division_id ?? $recEmployee->division_id,
                            'company_id' => $rec->company_id ?? $recEmployee->company_id,
                            'placement_id' => $rec->placement_id ?? $recEmployee->placement_id,
                            'last_contract_id' => $rec->last_contract_id ?? $recEmployee->last_contract_id,
                            'last_career_id' => $rec->last_career_id ?? $recEmployee->last_career_id,
                            'tax_id' => $rec->tax_id ?? $recEmployee->tax_id,
                            'nik' => $rec->nik ?? $recEmployee->nik,
                            'nickname' => $rec->nickname ?? $recEmployee->nickname,
                            'fullname' => $rec->fullname ?? $recEmployee->fullname,
                            'birth_place' => $rec->birth_place ?? $recEmployee->birth_place,
                            'birth_date' => $rec->birth_date ?? $recEmployee->birth_date,
                            'phone' => $rec->phone ?? $recEmployee->phone,
                            'email' => $rec->email ?? $recEmployee->email,
                            'gender_id' => $rec->gender_id ?? $recEmployee->gender_id,
                            'marital_id' => $rec->marital_id ?? $recEmployee->marital_id,
                            'religion_id' => $rec->religion_id ?? $recEmployee->religion_id,
                            'join_date' => $rec->join_date ?? $recEmployee->join_date,
                            'leave_saldo' => $rec->leave_saldo ?? $recEmployee->leave_saldo,
                            'address' => $rec->address ?? $recEmployee->address,
                            'address_city_id' => $rec->address_city_id ?? $recEmployee->address_city_id,
                            'address_province_id' => $rec->address_province_id ?? $recEmployee->address_province_id,
                            'address_permanent' => $rec->address_permanent ?? $recEmployee->address_permanent,
                            'address_permanent_city_id' => $rec->address_permanent_city_id ?? $recEmployee->address_permanent_city_id,
                            'address_permanent_province_id' => $rec->address_permanent_province_id ?? $recEmployee->address_permanent_province_id,
                            'bank_id' => $rec->bank_id ?? $recEmployee->bank_id,
                            'bank_account' => $rec->bank_account ?? $recEmployee->bank_account,
                            'bank_alias' => $rec->bank_alias ?? $recEmployee->bank_alias,
                            'emergency_relation_id' => $rec->emergency_relation_id ?? $recEmployee->emergency_relation_id,
                            'emergency_contact_name' => $rec->emergency_contact_name ?? $recEmployee->emergency_contact_name,
                            'emergency_contact_phone' => $rec->emergency_contact_phone ?? $recEmployee->emergency_contact_phone,
                            'emergency_contact_address' => $rec->emergency_contact_address ?? $recEmployee->emergency_contact_address,
                        ];

                        if ($rec->photo_id) {
                            if ($recEmployee->photo_id) {
                                FileUpload::removeFileById($recEmployee->photo_id);
                            }

                            $photo_id_new = Upload::find($rec->photo_id);
                            if ($photo_id_new) {
                                $filePath = storage_path('app/public/uploads/' . $photo_id_new->filename);
                                if (file_exists($filePath)) {
                                    $uploadedFile = new UploadedFile(
                                        $filePath,
                                        $photo_id_new->filename_origin,
                                        mime_content_type($filePath),
                                        null,
                                        true
                                    );

                                    $request->files->set('uploaded_photo', $uploadedFile);

                                    $id_photo = FileUpload::upload('uploaded_photo', 'employee');

                                    $updateData['photo_id'] = $id_photo;
                                }
                            }
                        }

                        foreach ($updateData as $key => $value) {
                            if ($value) {
                                $recEmployee->$key = $value;
                            }
                        }

                        $recEmployee->save();
                        DB::commit();
                        return response()->json([
                            'success' => true,
                            'message' => 'Request successfully approved and employee data updated.'
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Employee data not found!'
                        ], 404);
                    }
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Request data not found!'
                ], 404);
            }

            return response()->json([
                'success' => false,
                'message' => 'No ID provided!'
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error approving request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function reject(Request $request)
    {
        if (!in_array($request->user()->role->name, ['HRGA', 'SUPERADMIN', 'DEVELOPER'])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menyetujui/menolak pengajuan ini.'
            ], 403);
        }

        try {
            $id = $request->input('id');
            $note = $request->input('note');
            if ($id) {
                DB::beginTransaction();

                $rec = ModEmployeeRequest::find($id);
                if ($rec) {
                    $rec->approved_status = 0;
                    $rec->approved_at = now();
                    $rec->approved_by = $request->user()->id;
                    $rec->approved_note = $note;
                    $rec->save();

                    DB::commit();
                    return response()->json([
                        'success' => true,
                        'message' => 'Request successfully rejected.'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data not found!'
                    ], 404);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'No ID provided!'
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error rejecting request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->input('id');
            if ($id) {
                DB::beginTransaction();

                $rec = ModEmployeeRequest::find($id);
                if ($rec) {
                    $rec->delete();
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data not found!'
                    ], 404);
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Request successfully deleted.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No ID provided!'
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error deleting request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function exportPdf(Request $request, $id)
    {
        try {
            $user = auth()->user();
            $view = 'reports.cv_pdf';
            $rec = ModEmployee::with(['contracts', 'citizens', 'educations', 'careers', 'families', 'job_experiences', 'trainings'])->find($id);

            if (!$rec) {
                return response()->json(['success' => false, 'message' => 'Data not found'], 404);
            }

            $fileName = strtolower($rec->fullname);
            $fileName = preg_replace('/\s+/', '_', $fileName);
            $fileName = preg_replace('/[^a-z0-9_]/', '', $fileName);

            $photoUrl = $rec->photo_id ? public_path('/storage/uploads/' . $rec->photo->filename) : null;

            $params = ['user' => $user, 'data' => $rec, 'photoUrl' => $photoUrl];
            $html = view($view, $params)->render();
            $pdf = Pdf::loadHtml($html);

            return $pdf->download("Employee_Data_" . $fileName . ".pdf");
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error exporting PDF', 'error' => $e->getMessage()], 500);
        }
    }
}
