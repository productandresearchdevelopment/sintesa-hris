<?php

namespace App\Controllers\Admins\Appraisals;

use App\Http\Controllers\Controller;
use App\Libraries\Query;
use App\Models\Appraisals\AppraisalQuestion as Mod;
use Illuminate\Http\Request;

class AppraisalQuestion extends Controller
{
    public function data(Request $request, $counter = true)
    {
        $query = Mod::with(['category', 'template', 'template.division']);

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

        $result = Query::open($query, [
            'category.id',
            'category.name',
            'template.id',
            'template.title',
            'question',
            'group_kpi',
            'weight'
        ], $counter);

        return $result;
    }

    public function get(Request $request, $id = null)
    {
        return Mod::with(['category', 'template', 'template.division'])->where('id', $id)->first();
    }
}
