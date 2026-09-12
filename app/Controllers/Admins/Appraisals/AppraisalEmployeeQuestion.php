<?php

namespace App\Controllers\Admins\Appraisals;

use App\Http\Controllers\Controller;
use App\Libraries\ExportExcel;
use App\Libraries\FileUpload;
use App\Libraries\Query;
use App\Models\Appraisals\AppraisalEmployeeQuestion as Mod;
use App\SystemModels\Globals\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AppraisalEmployeeQuestion extends Controller
{
    public function data(Request $request, $counter = true)
    {
        $query = Mod::with(['appraisal_employee', 'question']);

        $result = Query::open($query, [], $counter);

        return $result;
    }

    public function get(Request $request, $id = null)
    {
        return Mod::with(['appraisal_employee', 'question'])->where('id', $id)->first();
    }
}
