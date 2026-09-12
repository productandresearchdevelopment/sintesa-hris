<?php

namespace App\Controllers\Admins\Bulletins;

use App\Http\Controllers\Controller;
use App\Libraries\Query;
use App\Models\Bulletins\BulletinCategory as Mod;
use App\Models\Organization;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulletinCategory extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $params = ['user' => $user];

        if ($user->role->name !== 'DEVELOPER' && $user->role->name !== 'SUPERADMIN') {
            return view('_front.bulletin.category.index', $params);
        } else {
            return view('_bak.bulletin_category.main', $params);
        }
    }


    public function data(Request $request)
    {
        $search = ['id', 'name', 'alias'];
        $query = Mod::with(['bulletins', 'bulletins.created_by', 'organizations'])
            ->withCount('bulletins');

        if (!$request->trash) {
            $query->withTrashed();
        } elseif ($request->trash == 2) {
            $query->onlyTrashed();
        }

        $result = Query::open($query, $search);

        $data = $result['data']->map(function ($item) {
            $item->count_bulletin = $item->bulletins_count;
            return $item;
        });

        return response()->json([
            'data' => $data,
            'count' => $result['count']
        ]);
    }


    public function get(Request $request, $id = null)
    {
        return Mod::where('id', $id)->with('bulletins', 'organizations', 'createdBy', 'updatedBy', 'deletedBy')->first();
    }

    public function view(Request $request, $id = null)
    {
        if ($data = Mod::find($id)) {
            $user = $request->user();
            $params = [
                'user' => $user,
                'data' => $data
            ];
            return view('_bak.bulletin_category.detail', $params);
        }
        abort('404');
    }

    public function dataOrganizations(Request $request, $categoryId = null)
    {
        if ($categoryId) {
            $data = $this->treeModules($categoryId);
            return response()->json($data);
        }
    }

    public function push(Request $request, $id = null)
    {
        DB::beginTransaction();
        try {
            $user = $request->user();

            $input = [
                'name' => $request->input('name'),
                'alias' => $request->input('alias'),
                'color' => $request->input('color'),
                'description' => $request->input('description'),
            ];

            if ($id) {
                $data = Mod::find($id);
                $data->update($input);
            } else $data = Mod::create($input);

            DB::commit();
            return ['success' => true, 'message' => 'Success...'];
        } catch (Exception $error) {
            DB::rollback();
            return ['success' => false, 'message' => '500 ' . $error->getMessage()];
        }
    }

    public function setOrganization(Request $request, $categoryId)
    {
        $input  = $request->all();
        $organization  = $input['organization'];
        $auth = $input['auth'];

        if ($categoryId && $organization) {
            $data   = Mod::find($categoryId);
            $data->organizations()->detach([$organization]);
            if ($auth) $data->organizations()->attach([$organization]);
            return response()->json(['success' => true, 'message' => 'Success!!!']);
        }
        return response()->json(['success' => false, 'message' => 'Organization Not Found']);
    }

    public function delete(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($rec = Mod::where('id', $id)->withTrashed()->first()) {
                    $bulletins = $rec->bulletins;
                    if (count($bulletins) > 0) {
                        $bulletins->each(function ($bulletin) {
                            $bulletin->delete();
                        });
                    }
                    $rec->delete();
                }
            }
            return ['success' => true, 'message' => 'Success!'];
        }
        return ['success' => false, 'message' => 'No Data!'];
    }

    public function restore(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($rec = Mod::where('id', $id)->withTrashed()->first()) {
                    $bulletins = $rec->bulletins()->withTrashed()->get();
                    if (count($bulletins) > 0) {
                        $bulletins->each(function ($bulletin) {
                            $bulletin->restore();
                        });
                    }
                    $rec->restore();
                }
            }
            return ['success' => true, 'message' => 'Success!'];
        }
        return ['success' => false, 'message' => 'No Data!'];
    }

    public function forceDelete(Request $request)
    {
        if ($data = json_decode($request->data)) {
            DB::beginTransaction();

            try {
                foreach ($data as $id) {
                    if ($rec = Mod::where('id', $id)->withTrashed()->first()) {
                        $bulletins = $rec->bulletins;

                        if (count($bulletins) > 0) {
                            DB::rollBack();
                            return response()->json([
                                'success' => false,
                                'message' => 'Failed, bulletin category has bulletin data.'
                            ], 422);
                        }

                        $rec->forceDelete();
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Success!'
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
            'message' => 'No Data!'
        ], 400);
    }

    private function treeModules($categoryId, $parent = null)
    {
        $result = Organization::where('parent_id', $parent)
            ->orderBy('name')
            ->get();
        $category = Mod::find($categoryId);

        foreach ($result as $row) {
            $row->children = $this->treeModules($category->id, $row->id);
            $row->leaf = (count($row->children)) ? false : true;
            $row->checked = $row->hasBulletinCategory($category->id);
            $row->icon = asset('images/icons/' . ($row->type->icon ?? 'home') . '.png');
            $row->home = ($category->home == $row->id);
        }

        return $result;
    }
}
