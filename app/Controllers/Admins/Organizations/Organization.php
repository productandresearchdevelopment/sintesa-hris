<?php

namespace App\Controllers\Admins\Organizations;

use App\Http\Controllers\Controller;
use App\Models\Organization as Mod;
use App\Traits\UserScopingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Organization extends Controller
{
    use UserScopingTrait;

    public function index(Request $request)
    {
        $user = $request->user();
        $parent = Mod::find(1);
        $params = [
            'user' => $user,
            'parent' => $parent,
        ];
        return view('_bak.organization.main', $params);
    }

    public function data(Request $request)
    {
        $user = $request->user();
        $isSuper = $this->isSuperUser($user);
        $userCompany = $this->getUserCompanyId($user);

        $company = $request->input('company_id');
        $targetCompany = $company ? (int) $company : (!$isSuper ? $userCompany : null);

        $data = $this->buildCompanyOrgTree($targetCompany);
        return response()->json($data);
    }

    private function buildCompanyOrgTree($userCompany = null)
    {
        $companyQuery = \App\Models\Company::whereNull('deleted_at')->orderBy('name');
        if ($userCompany !== null) {
            $companyQuery->where('id', $userCompany);
        }

        $companies = $companyQuery->get();
        $tree = [];

        foreach ($companies as $company) {
            $children = $this->treeOrg($company->id, null);
            $tree[] = [
                'id' => 'company_' . $company->id,
                'name' => $company->name,
                'text' => $company->name,
                'leaf' => count($children) === 0,
                'icon' => asset('images/icons/home.png'),
                'expanded' => true,
                'children' => $children,
            ];
        }

        return $tree;
    }

    private function treeOrg($companyId, $parentId = null)
    {
        $orgs = Mod::where('company_id', $companyId)
            ->where('parent_id', $parentId)
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        $result = [];

        foreach ($orgs as $row) {
            $children = $this->treeOrg($companyId, $row->id);
            $auth1 = $row->authorized1 ? Mod::find($row->authorized1) : null;
            $auth2 = $row->authorized2 ? Mod::find($row->authorized2) : null;

            $result[] = [
                'id' => (int) $row->id,
                'name' => $row->name,
                'text' => $row->name,
                'leaf' => count($children) === 0,
                'icon' => asset('images/icons/' . ($row->type->icon ?? 'home') . '.png'),
                'parent_id' => $row->parent_id ?? 0,
                'alias' => $row->alias ?? '',
                'description' => $row->description ?? '',
                'position_id' => $row->position_id ?? '',
                'division_id' => $row->division_id ?? 0,
                'company_id' => $row->company_id ?? 0,
                'deleted_at' => $row->deleted_at,
                'authorized1' => $auth1 ? ['id' => $auth1->id, 'name' => $auth1->name] : null,
                'authorized2' => $auth2 ? ['id' => $auth2->id, 'name' => $auth2->name] : null,
                'children' => $children,
                'expanded' => true,
            ];
        }

        return $result;
    }

    public function path(int|string $id)
    {
        $path = [];
        $current = Mod::findOrFail($id);
        while ($current) {
            array_unshift($path, (string) $current->id);
            $current = $current->parent_id ? Mod::find($current->parent_id) : null;
        }
        array_unshift($path, '0');
        return response()->json($path);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_id' => 'nullable',
            'name' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255',
            'division_id' => 'nullable|exists:iq_division,id',
            'company_id' => 'nullable|exists:iq_company,id',
            'position_id' => 'nullable|exists:iq_global_data,id',
            'authorized1' => 'nullable',
            'authorized2' => 'nullable',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'success' => false
            ], 422);
        }

        $user = $request->user();
        $companyId = $this->isSuperUser($user)
            ? $request->input('company_id')
            : $this->getUserCompanyId($user);

        DB::beginTransaction();
        try {
            $folder = Mod::create([
                'parent_id'   => $this->normalizeZeroToNull($request->input('parent_id')),
                'name'        => $request->input('name'),
                'alias'       => $request->input('alias'),
                'division_id' => $request->input('division_id'),
                'company_id'  => $companyId,
                'position_id' => $request->input('position_id'),
                'authorized1' => $this->normalizeZeroToNull($request->input('authorized1')),
                'authorized2' => $this->normalizeZeroToNull($request->input('authorized2')),
                'description' => $request->input('description'),
            ]);

            $folder->setPath($folder->id);
            $folder->leaf = false;
            $folder->icon = asset('images/icons/folder.png');

            DB::commit();
            return response()->json([
                'data' => $folder,
                'message' => "Data created successfully",
                'success' => true
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create data: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function update(Request $request, int|string $id)
    {
        $validator = Validator::make($request->all(), [
            'parent_id' => 'nullable',
            'name' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255',
            'division_id' => 'nullable|exists:iq_division,id',
            'company_id' => 'nullable|exists:iq_company,id',
            'position_id' => 'nullable|exists:iq_global_data,id',
            'authorized1' => 'nullable',
            'authorized2' => 'nullable',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'success' => false
            ], 422);
        }

        $folder = Mod::find($id);

        if (!$folder) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.'
            ], 404);
        }

        $user = $request->user();
        if (!$this->isSuperUser($user)) {
            $userCompanyId = $this->getUserCompanyId($user);
            if ($folder->company_id !== $userCompanyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action for this company.'
                ], 403);
            }
        }

        DB::beginTransaction();
        try {
            if ($request->has('parent_id')) {
                $folder->parent_id = $this->normalizeZeroToNull($request->input('parent_id'));
            }

            if ($request->has('name')) {
                $folder->name = $request->input('name');
            }

            if ($request->has('alias')) {
                $folder->alias = $request->input('alias');
            }

            if ($request->has('division_id')) {
                $folder->division_id = $request->input('division_id');
            }

            if ($this->isSuperUser($user) && $request->has('company_id')) {
                $folder->company_id = $request->input('company_id');
            }

            if ($request->has('position_id')) {
                $folder->position_id = $request->input('position_id');
            }

            if ($request->has('authorized1')) {
                $folder->authorized1 = $this->normalizeZeroToNull($request->input('authorized1'));
            }

            if ($request->has('authorized2')) {
                $folder->authorized2 = $this->normalizeZeroToNull($request->input('authorized2'));
            }

            if ($request->has('description')) {
                $folder->description = $request->input('description');
            }

            $folder->save();

            DB::commit();
            return response()->json(['data' => $folder, 'message' => "Data update successfully", 'success' => true], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update data: ' . $e->getMessage(), 'success' => false], 500);
        }
    }

    public function move(Request $request, string $mode)
    {
        $input  = $request->all();
        $from   = json_decode($input['from']);
        $to     = json_decode($input['to']);

        if (!$from || !$to) {
            return response()->json(['success' => false, 'message' => 'No data provided or invalid format.'], 400);
        }

        $user = $request->user();
        if (!$this->isSuperUser($user)) {
            $userCompanyId = $this->getUserCompanyId($user);
            $fromOrg = Mod::find($from->id);
            $toOrg = Mod::find($to->id);
            if (($fromOrg && $fromOrg->company_id !== $userCompanyId) || ($toOrg && $toOrg->company_id !== $userCompanyId)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action for this company.'], 403);
            }
        }

        if ($mode == 'append') {
            Mod::find($from->id)->update(['parent_id' => $to->id]);
            Mod::resorting($to->id);
            return response()->json(['success' => true, 'message' => 'Success!']);
        } elseif ($mode == 'before' || $mode == 'after') {
            Mod::find($from->id)->update(['parent_id' => $to->parent_id]);
            Mod::resorting($from->parent_id);
            Mod::setPath($from->id);
            return response()->json(['success' => true, 'message' => 'Success!']);
        }

        return response()->json(['success' => false, 'message' => 'No data provided or invalid format.'], 400);
    }

    public function setDivision(Request $request, int|string $organizationId)
    {
        $input  = $request->all();
        $division  = $input['division_id'];

        $organization = Mod::find($organizationId);
        if (!$organization) {
            return response()->json(['success' => false, 'message' => 'Organization not found!']);
        }

        $user = $request->user();
        if (!$this->isSuperUser($user)) {
            $userCompanyId = $this->getUserCompanyId($user);
            if ($organization->company_id !== $userCompanyId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action for this company.'], 403);
            }
        }

        if ($organization->childs()->count() > 0) {
            $organization->childs()->update(['division_id' => $division]);
        }

        $organization->division_id = $division;
        $organization->save();

        return response()->json(['success' => true, 'message' => 'Division updated successfully for the organization and its children.']);
    }

    public function delete(Request $request)
    {
        if ($data = json_decode($request->data)) {
            $user = $request->user();
            $userCompanyId = $this->getUserCompanyId($user);
            $isSuper = $this->isSuperUser($user);

            DB::beginTransaction();
            try {
                foreach ($data as $id) {
                    $org = Mod::find($id);
                    if ($org && ($isSuper || $org->company_id === $userCompanyId)) {
                        $this->handleDelete($id);
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => "Data soft-deleted successfully",
                    'data' => null
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to soft delete data: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'No data provided or invalid format.'
        ], 400);
    }

    public function restore(Request $request)
    {
        if ($data = json_decode($request->data)) {
            $user = $request->user();
            $userCompanyId = $this->getUserCompanyId($user);
            $isSuper = $this->isSuperUser($user);

            DB::beginTransaction();
            try {
                foreach ($data as $id) {
                    $org = Mod::withTrashed()->find($id);
                    if ($org && ($isSuper || $org->company_id === $userCompanyId)) {
                        $this->handleRestore($id);
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => "Data restored successfully",
                    'data' => null
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to restore data: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'No data provided or invalid format.'
        ], 400);
    }

    public function forceDelete(Request $request)
    {
        if ($data = json_decode($request->data)) {
            $user = $request->user();
            $userCompanyId = $this->getUserCompanyId($user);
            $isSuper = $this->isSuperUser($user);

            DB::beginTransaction();
            try {
                foreach ($data as $id) {
                    $org = Mod::withTrashed()->find($id);
                    if ($org && ($isSuper || $org->company_id === $userCompanyId)) {
                        $this->handleForceDelete($id);
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data' => null
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete data: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'No data provided or invalid format.'
        ], 400);
    }

    private function handleDelete(int|string $id): void
    {
        $rec = Mod::where('id', $id)->withTrashed()->first();
        if ($rec) {
            $rec->delete();
            $children = Mod::where('parent_id', $rec->id)->get();
            foreach ($children as $child) {
                $this->handleDelete($child->id);
            }
        }
    }

    private function handleRestore(int|string $id): void
    {
        $rec = Mod::where('id', $id)->withTrashed()->first();
        if ($rec) {
            $rec->restore();
            $children = Mod::where('parent_id', $rec->id)->onlyTrashed()->get();
            foreach ($children as $child) {
                $this->handleRestore($child->id);
            }
        }
    }

    private function handleForceDelete(int|string $id): void
    {
        $rec = Mod::where('id', $id)->withTrashed()->first();
        if ($rec) {
            $rec->forceDelete();
            $children = Mod::where('parent_id', $rec->id)->withTrashed()->get();
            foreach ($children as $child) {
                $this->handleForceDelete($child->id);
            }
        }
    }

    private function normalizeZeroToNull(mixed $value): mixed
    {
        return ($value === 0 || $value === '0') ? null : $value;
    }
}
