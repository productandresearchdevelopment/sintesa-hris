<?php

namespace App\Controllers\Admins\Bulletins;

use App\Http\Controllers\Controller;
use App\Libraries\Query;
use App\Models\Bulletins\BulletinCategory as Mod;
use App\Models\Organization;
use App\Traits\UserScopingTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulletinCategory extends Controller
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

        if (!in_array($user->role->name, ['DEVELOPER', 'SUPERADMIN', 'ADMINISTRATOR', 'HRGA'])) {
            return view('_front.bulletin.category.index', $params);
        } else {
            return view('_bak.bulletin_category.main', $params);
        }
    }

    public function data(Request $request)
    {
        $user = $request->user();
        $isSuperUser = $this->isSuperUser($user);
        $userCompany = $this->getUserCompanyId($user);

        $search = ['id', 'name', 'alias', 'description', 'company.name'];
        $query = Mod::with(['bulletins', 'bulletins.created_by', 'organizations', 'company'])
            ->withCount('bulletins');

        if (!$isSuperUser && $userCompany) {
            $query->where(function ($q) use ($userCompany) {
                $q->whereNull('company_id')
                    ->orWhere('company_id', $userCompany)
                    ->orWhereHas('organizations', fn($orgQ) => $orgQ->where('company_id', $userCompany));
            });
        } elseif ($isSuperUser && $request->filled('company_id')) {
            $query->where('company_id', (int) $request->input('company_id'));
        }

        if (!$request->trash) {
            $query->withTrashed();
        } elseif ($request->trash == 2) {
            $query->onlyTrashed();
        }

        $result = Query::open($query, $search);

        $data = $result['data']->map(function ($item) {
            $item->count_bulletin = $item->bulletins_count;
            $item->company_name = $item->company?->name ?? 'GLOBAL';
            return $item;
        });

        return response()->json([
            'data' => $data,
            'count' => $result['count']
        ]);
    }

    public function get(Request $request, int|string|null $id = null)
    {
        $user = $request->user();
        $isSuperUser = $this->isSuperUser($user);
        $userCompany = $this->getUserCompanyId($user);

        $query = Mod::where('id', $id)->with('bulletins', 'organizations', 'company', 'createdBy', 'updatedBy', 'deletedBy');

        if (!$isSuperUser && $userCompany) {
            $query->where(function ($q) use ($userCompany) {
                $q->whereNull('company_id')
                    ->orWhere('company_id', $userCompany)
                    ->orWhereHas('organizations', fn($orgQ) => $orgQ->where('company_id', $userCompany));
            });
        }

        return $query->first();
    }

    public function view(Request $request, int|string|null $id = null)
    {
        $data = $this->get($request, $id);
        if ($data) {
            $user = $request->user();
            $params = [
                'user' => $user,
                'data' => $data,
                'isSuperUser' => $this->isSuperUser($user),
            ];
            return view('_bak.bulletin_category.detail', $params);
        }
        abort('404');
    }

    public function dataOrganizations(Request $request, int|string|null $categoryId = null)
    {
        if ($categoryId) {
            $user = $request->user();
            $isSuperUser = $this->isSuperUser($user);
            $userCompany = $this->getUserCompanyId($user);

            $data = $this->buildCompanyOrgTree($categoryId, !$isSuperUser ? $userCompany : null);
            return response()->json($data);
        }
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
            $companyId = $isSuperUser
                ? ($request->filled('company_id') ? (int) $request->input('company_id') : null)
                : ($userCompany ? (int) $userCompany : null);

            $input = [
                'name' => $request->input('name'),
                'alias' => $request->input('alias'),
                'color' => $request->input('color'),
                'description' => $request->input('description'),
                'company_id' => $companyId,
            ];

            if ($id) {
                $data = Mod::find($id);
                if (!$data) {
                    return response()->json(['success' => false, 'message' => 'Category not found'], 404);
                }
                if (!$isSuperUser && $data->company_id !== null && (int) $data->company_id !== (int) $userCompany) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
                }
                $data->update($input);
            } else {
                $data = Mod::create($input);
            }

            DB::commit();
            return ['success' => true, 'message' => 'Success...'];
        } catch (Exception $error) {
            DB::rollback();
            return ['success' => false, 'message' => '500 ' . $error->getMessage()];
        }
    }

    public function setOrganization(Request $request, int|string $categoryId)
    {
        $input = $request->all();
        $organization = $input['organization'];
        $auth = $input['auth'];

        $user = $request->user();
        $isSuperUser = $this->isSuperUser($user);
        $userCompany = $this->getUserCompanyId($user);

        if ($categoryId && $organization) {
            $org = Organization::find($organization);
            if (!$org) {
                return response()->json(['success' => false, 'message' => 'Organization Not Found'], 404);
            }

            if (!$isSuperUser && $userCompany && $org->company_id != $userCompany) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action on this organization'], 403);
            }

            $data = Mod::find($categoryId);
            if (!$data) {
                return response()->json(['success' => false, 'message' => 'Category Not Found'], 404);
            }

            $data->organizations()->detach([$organization]);
            if ($auth) {
                $data->organizations()->attach([$organization]);
            }
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

    private function buildCompanyOrgTree($categoryId, $userCompany = null)
    {
        $companyQuery = \App\Models\Company::whereNull('deleted_at')->orderBy('name');
        if ($userCompany !== null) {
            $companyQuery->where('id', $userCompany);
        }

        $companies = $companyQuery->get();
        $tree = [];

        foreach ($companies as $company) {
            $children = $this->treeOrg($categoryId, $company->id, null);
            $tree[] = [
                'id' => 'company_' . $company->id,
                'name' => $company->name,
                'leaf' => count($children) === 0,
                'icon' => asset('images/icons/home.png'),
                'expanded' => true,
                'children' => $children,
            ];
        }

        return $tree;
    }

    private function treeOrg($categoryId, $companyId, $parentId = null)
    {
        $orgs = Organization::where('company_id', $companyId)
            ->where('parent_id', $parentId)
            ->orderBy('name')
            ->get();

        $category = Mod::find($categoryId);
        $result = [];

        foreach ($orgs as $row) {
            $children = $this->treeOrg($categoryId, $companyId, $row->id);
            $result[] = [
                'id' => (int) $row->id,
                'name' => $row->name,
                'leaf' => count($children) === 0,
                'checked' => $category ? $row->hasBulletinCategory($category->id) : false,
                'icon' => asset('images/icons/' . ($row->type->icon ?? 'home') . '.png'),
                'home' => ($category && $category->home == $row->id),
                'children' => $children,
                'expanded' => true,
            ];
        }

        return $result;
    }
}
