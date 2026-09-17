<?php

namespace App\Controllers\Front\Leaves;

use App\Http\Controllers\Controller;
use App\Libraries\Query;
use App\Models\Company;
use App\Models\GlobalData as Mod;
use App\Traits\UserScopingTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Type extends Controller
{
    use UserScopingTrait;

    public function index(Request $request)
    {
        $user = $request->user();
        $isSuperUser = $this->isSuperUser($user);
        $userCompany = $this->getUserCompanyId($user);

        $params = [
            'user' => $user,
            'isSuperUser' => $isSuperUser,
            'userCompany' => $userCompany,
        ];

        return view('_front.leave.type.main', $params);
    }

    public function data(Request $request)
    {
        $user = $request->user();
        $isSuperUser = $this->isSuperUser($user);
        $userCompany = $this->getUserCompanyId($user);

        $search = ['id', 'name', 'alias', 'description'];
        $query = Mod::where('group', 'leave_type');

        if (!$isSuperUser && $userCompany) {
            $query->where(function ($q) use ($userCompany) {
                $q->whereNull('property->company_id')
                    ->orWhere('property->company_id', $userCompany);
            });
        } elseif ($isSuperUser && $request->filled('company_id')) {
            $query->where('property->company_id', (int) $request->input('company_id'));
        }

        $companies = Company::whereNull('deleted_at')->pluck('name', 'id')->toArray();

        $result = Query::open($query, $search);
        $result['data'] = collect($result['data'])->map(function ($item) use ($companies) {
            $item->flag_reduce_balance = filter_var($item->property->flag_reduce_balance ?? false, FILTER_VALIDATE_BOOLEAN);
            $compCandidateId = $item->property->company_id ?? null;
            $item->company_id = $compCandidateId ? (int) $compCandidateId : null;
            $item->company_name = $item->company_id ? ($companies[$item->company_id] ?? 'Company #' . $item->company_id) : 'GLOBAL';
            return $item;
        });

        return response()->json([
            'data' => $result['data'],
            'count' => $result['count']
        ]);
    }

    public function get(Request $request, int|string|null $id = null)
    {
        $user = $request->user();
        $isSuperUser = $this->isSuperUser($user);
        $userCompany = $this->getUserCompanyId($user);

        $query = Mod::where('id', $id)->where('group', 'leave_type');
        if (!$isSuperUser && $userCompany) {
            $query->where(function ($q) use ($userCompany) {
                $q->whereNull('property->company_id')
                    ->orWhere('property->company_id', $userCompany);
            });
        }

        $data = $query->first();

        if ($data) {
            $data->flag_reduce_balance = filter_var($data->property->flag_reduce_balance ?? false, FILTER_VALIDATE_BOOLEAN);
            $data->company_id = isset($data->property->company_id) && $data->property->company_id ? (int) $data->property->company_id : null;
        }

        return $data;
    }

    public function push(Request $request, int|string|null $id = null)
    {
        $user = $request->user();
        if (!$this->isSuperUser($user) && !$this->isHrga($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        $isSuperUser = $this->isSuperUser($user);
        $userCompany = $this->getUserCompanyId($user);

        DB::beginTransaction();
        try {
            $flagReduceBalance = filter_var($request->input('flag_reduce_balance'), FILTER_VALIDATE_BOOLEAN);

            $companyId = $isSuperUser
                ? ($request->filled('company_id') ? (int) $request->input('company_id') : null)
                : ($userCompany ? (int) $userCompany : null);

            $property = (object) [
                'flag_reduce_balance' => $flagReduceBalance,
                'company_id' => $companyId,
            ];

            $input = [
                'group' => 'leave_type',
                'name' => $request->input('name'),
                'alias' => $request->input('alias'),
                'color' => $request->input('color'),
                'description' => $request->input('description'),
                'property' => $property,
            ];

            if ($id) {
                $data = Mod::where('id', $id)->where('group', 'leave_type')->first();
                if (!$data) {
                    return response()->json(['success' => false, 'message' => 'Leave type not found'], 404);
                }

                $existingCompanyId = $data->property->company_id ?? null;
                if (!$isSuperUser && $existingCompanyId !== null && (int) $existingCompanyId !== (int) $userCompany) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
                }

                $data->update($input);
            } else {
                $data = Mod::create($input);
            }

            DB::commit();
            return ['success' => true, 'message' => 'Success'];
        } catch (Exception $error) {
            DB::rollBack();
            return ['success' => false, 'message' => '500 ' . $error->getMessage()];
        }
    }

    public function delete(Request $request)
    {
        $user = $request->user();
        if (!$this->isSuperUser($user) && !$this->isHrga($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        $isSuperUser = $this->isSuperUser($user);
        $userCompany = $this->getUserCompanyId($user);

        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                $rec = Mod::where('id', $id)->where('group', 'leave_type')->first();
                if ($rec) {
                    if (!$isSuperUser) {
                        $recCompanyId = $rec->property->company_id ?? null;
                        if ($recCompanyId === null || (int) $recCompanyId !== (int) $userCompany) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Cannot delete system default or other company leave types!'
                            ], 403);
                        }
                    }

                    $leaves = $rec->leaves;

                    if (count($leaves) > 0) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed remove, this type has related data!'
                        ], 422);
                    }

                    $rec->delete();
                }
            }
            return ['success' => true, 'message' => 'Success!'];
        }
        return ['success' => false, 'message' => 'No Data!'];
    }
}
