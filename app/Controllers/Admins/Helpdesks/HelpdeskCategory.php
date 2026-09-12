<?php

namespace App\Controllers\Admins\Helpdesks;

use App\Http\Controllers\Controller;
use App\Libraries\Query;
use App\Models\Helpdesks\HelpdeskCategory as ModCategory;
use App\Models\Helpdesks\Helpdesk;
use App\Models\Helpdesks\HelpdeskCategoryOrganization;
use App\Models\Organization as Mod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HelpdeskCategory extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $params = [
            'user' => $user,
            'organization' => Mod::all(),
        ];
        return view('_bak.helpdesk.category.main', $params);
    }

    public function data(Request $request)
    {

        $query = ModCategory::with([
            'organizations'
        ]);

        if ($request->input('folder')) {
            $data = HelpdeskCategoryOrganization::where('organization_id', $request->input('folder'))->get();
            $query->whereIn('id', $data->pluck('category_id'));
        }

        // Ini filter berdasarkan hirarki folder

        // if ($request->input('folder')) {
        //     $organizationId = $request->input('folder');
        //     $rootOrganization = Mod::find($organizationId);

        //     if ($rootOrganization) {
        //         $organizationIds = Mod::where('path', 'like', $rootOrganization->path . '%')->pluck('id');

        //         $data = HelpdeskCategoryOrganization::whereIn('organization_id', $organizationIds)->get();
        //         $query->whereIn('id', $data->pluck('category_id'));
        //     }
        // }

        if (!$request->trash) {
            $query->withTrashed();
        }
        if ($request->trash == 2) {
            $query->onlyTrashed();
        }

        $searchFields = [
            'name',
            'description'
        ];

        return Query::open($query, $searchFields);
    }

    public function dataOrganizations(Request $request, $categoryId = null)
    {
        if ($categoryId) {
            $data = $this->treeModules($categoryId);
            return response()->json($data);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
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

            $category = ModCategory::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ]);

            DB::commit();
            return response()->json([
                'data' => $category,
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'success' => false
            ], 422);
        }

        $category = ModCategory::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.'
            ], 404);
        }

        DB::beginTransaction();
        try {

            if ($request->has('name')) {
                $category->name = $request->input('name');
            }

            if ($request->has('description')) {
                $category->description = $request->input('description');
            }

            $category->save();

            DB::commit();
            return response()->json(['data' => $category, 'message' => "Data update successfully", 'success' => true], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update data: ' . $e->getMessage(), 'success' => false], 500);
        }
    }

    public function setOrganization(Request $request, $categoryId)
    {
        $input  = $request->all();
        $organization  = $input['organization'];
        $auth = $input['auth'];

        if ($categoryId && $organization) {
            $data   = ModCategory::find($categoryId);
            $data->organizations()->detach([$organization]);
            if ($auth) $data->organizations()->attach([$organization]);
            return response()->json(['success' => true, 'message' => 'Success!!!']);
        }
        return response()->json(['success' => false, 'message' => 'Organization Not Found']);
    }

    public function delete(Request $request)
    {
        if ($data = json_decode($request->data)) {
            DB::beginTransaction();
            try {
                foreach ($data as $id) {
                    if ($category = ModCategory::find($id)) {
                        $category->delete();
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

    public function forceDelete(Request $request)
    {
        if ($data = json_decode($request->data)) {
            DB::beginTransaction();
            try {
                foreach ($data as $id) {
                    if ($category = ModCategory::withTrashed()->find($id)) {
                        $helpdesk = Helpdesk::where('category_id', $id)->first();
                        if ($helpdesk) {
                            $helpdesk->category_id = null;
                            $helpdesk->save();
                        }
                        $category->forceDelete();
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

    public function restore(Request $request)
    {
        if ($data = json_decode($request->data)) {
            DB::beginTransaction();
            try {
                foreach ($data as $id) {
                    if ($category = ModCategory::withTrashed()->find($id)) {
                        $category->restore();
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

    private function treeModules($categoryId, $parent = null)
    {
        $result = Mod::where('parent_id', $parent)
            ->orderBy('name')
            ->get();
        $category = ModCategory::find($categoryId);

        foreach ($result as $row) {
            $row->children = $this->treeModules($category->id, $row->id);
            $row->leaf = (count($row->children)) ? false : true;
            $row->checked = $row->hasHelpdeskCategory($category->id);
            $row->icon = asset('images/icons/' . ($row->type->icon ?? 'home') . '.png');
            $row->home = ($category->home == $row->id);
        }

        return $result;
    }
}
