<?php

namespace App\Controllers\Admins\Appraisals;

use App\Http\Controllers\Controller;
use App\Libraries\Query;
use App\Models\Appraisals\AppraisalQuestion as Mod;
use App\Traits\UserScopingTrait;
use Illuminate\Http\Request;

class AppraisalQuestion extends Controller
{
    use UserScopingTrait;

    public function data(Request $request, $counter = true)
    {
        $user = $request->user();
        $query = Mod::with(['category', 'template', 'template.division']);

        if (!$this->isSuperUser($user)) {
            $userCompany = $this->getUserCompanyId($user);
            if ($userCompany) {
                $query->whereHas('template', function ($tq) use ($userCompany) {
                    $tq->whereHas('templates_organizations', function ($to) use ($userCompany) {
                        $to->where('company_id', $userCompany);
                    });
                });
            }
        }

        if (!$request->trash) {
            $query->withTrashed();
        }
        if ($request->trash == 2) {
            $query->onlyTrashed();
        }

        if ($request->filled('template') && $request->template !== 'all' && $request->template != 0) {
            $query->where('template_id', $request->template);
        }

        if ($request->filled('category') && $request->category !== 'all' && $request->category != 0) {
            $query->where('category_id', $request->category);
        }

        return Query::open($query, [
            'category.id',
            'category.name',
            'template.id',
            'template.title',
            'question',
            'group_kpi',
            'weight'
        ], $counter);
    }

    public function get(Request $request, $id = null)
    {
        return Mod::with(['category', 'template', 'template.division'])->where('id', $id)->first();
    }
}
