<?php

namespace App\Controllers\Admins\Appraisals;

use App\Http\Controllers\Controller;
use App\Libraries\Query;
use App\Models\Appraisals\AppraisalQuestionCategory as Mod;
use Illuminate\Http\Request;

class AppraisalQuestionCategory extends Controller
{
    public function data(Request $request, $counter = true)
    {
        $query = Mod::query();

        $result = Query::open($query, [], $counter);

        return $result;
    }
}
