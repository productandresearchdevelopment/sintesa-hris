<?php

namespace App\Controllers\Admins\Organizations;

use App\Http\Controllers\Controller;
use App\Models\Organization as Mod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Organization extends Controller
{
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
        $node    = $request->input('node', '0');
        $company = $request->input('company_id');
        $filter  = $request->input('filter');

        $user = $request->user();
        $roleName = strtolower(optional(optional($user)->role)->name ?? '');
        $isSuperUser = in_array($roleName, ['superadmin', 'developer']);
        $userCompany = optional(optional($user)->employee)->company_id ?? optional($user)->company_id;

        if (!$isSuperUser && $userCompany) {
            $company = $userCompany;
        }

        $base = Mod::query();

        if ($filter === 'trash') {
            $base = Mod::onlyTrashed();
        } elseif ($filter === 'all trash') {
            $base = Mod::withTrashed();
        } elseif ($filter === 'active') {
            $base->whereNull('deleted_at');
        }

        if (!empty($company)) {
            $base->where(function ($q) use ($company) {
                $q->where('company_id', $company)
                    ->orWhereNull('company_id');
            });
        }

        $q = (clone $base);

        if ($node === '0' || $node === 0) {
            $q->whereNull('parent_id');
        } else {
            $q->where('parent_id', $node);
        }

        $rows = $q->orderBy('name')->get();

        $hasChildren = function ($parentId) use ($base) {
            return (clone $base)->where('parent_id', $parentId)->exists();
        };

        return $rows->map(function ($row) use ($hasChildren) {
            $auth1 = $row->authorized1 ? Mod::find($row->authorized1) : null;
            $auth2 = $row->authorized2 ? Mod::find($row->authorized2) : null;

            return [
                'id'          => (string) $row->id,
                'text'        => $row->name,
                'leaf'        => !$hasChildren($row->id),
                'icon'        => asset('images/icons/folder.png'),
                'parent_id'   => $row->parent_id ?? 0,
                'alias'       => $row->alias ?? '',
                'description' => $row->description ?? '',
                'position_id' => $row->position_id ?? '',
                'division_id' => $row->division_id ?? 0,
                'company_id'  => $row->company_id ?? 0,
                'deleted_at'  => $row->deleted_at,
                'authorized1' => $auth1 ? ['id' => $auth1->id, 'name' => $auth1->name] : null,
                'authorized2' => $auth2 ? ['id' => $auth2->id, 'name' => $auth2->name] : null,
            ];
        });
    }

    public function path($id)
    {
        $path = [];
        $current = Mod::findOrFail($id);
        while ($current) {
            array_unshift($path, (string) $current->id);
            $current = $current->parent_id
                ? Mod::find($current->parent_id)
                : null;
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

        DB::beginTransaction();
        try {
            $folder = Mod::create([
                'parent_id'   => $this->normalizeZeroToNull($request->input('parent_id')),
                'name'        => $request->input('name'),
                'alias'       => $request->input('alias'),
                'division_id' => $request->input('division_id'),
                'company_id'  => $request->input('company_id'),
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


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),  [
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

        DB::beginTransaction();
        try {
            $folder->update([
                'parent_id'   => $this->normalizeZeroToNull($request->input('parent_id')),
                'name'        => $request->input('name'),
                'alias'       => $request->input('alias'),
                'division_id' => $request->input('division_id'),
                'company_id'  => $request->input('company_id'),
                'position_id' => $request->input('position_id'),
                'authorized1' => $this->normalizeZeroToNull($request->input('authorized1')),
                'authorized2' => $this->normalizeZeroToNull($request->input('authorized2')),
                'description' => $request->input('description'),
            ]);
            $folder->setPath($id);

            DB::commit();
            return response()->json(['data' => $folder, 'message' => "Data update successfully", 'success' => true], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update data: ' . $e->getMessage(), 'success' => false], 500);
        }
    }

    public function move(Request $request, $mode)
    {
        $input  = $request->all();
        $from   = json_decode($input['from']);
        $to     = json_decode($input['to']);

        if ($mode == 'append') {
            Mod::find($from->id)->update(['parent_id' => $to->id]);
            Mod::resorting($to->id);

            return response()->json(['success' => true, 'message' => 'Success!!!']);
        } elseif ($mode == 'before' || $mode == 'after') {
            Mod::find($from->id)->update(['parent_id' => $to->parent_id]);
            Mod::resorting($from->parent_id);
            Mod::setPath($from->id);

            return response()->json(['success' => true, 'message' => 'Success!!!']);
        }

        return response()->json(['success' => false, 'message' => 'No data provided or invalid format.'], 400);
    }

    public function setDivision(Request $request, $organizationId)
    {
        $input  = $request->all();
        $division  = $input['division_id'];

        $organization = Mod::find($organizationId);

        if (!$organization) {
            return response()->json(['success' => false, 'message' => 'Organization not found!']);
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
            DB::beginTransaction();
            try {
                foreach ($data as $id) {
                    $this->handleDelete($id);
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
            DB::beginTransaction();
            try {
                foreach ($data as $id) {
                    $this->handleRestore($id);
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
            DB::beginTransaction();
            try {
                foreach ($data as $id) {
                    $this->handleForceDelete($id);
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

    private function handleDelete($id)
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

    private function handleRestore($id)
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

    private function handleForceDelete($id)
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

    private function normalizeZeroToNull($value)
    {
        return ($value === 0 || $value === '0') ? null : $value;
    }
}
