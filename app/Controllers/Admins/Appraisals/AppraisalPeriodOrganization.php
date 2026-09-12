<?php

namespace App\Controllers\Admins\Appraisals;

use App\Http\Controllers\Controller;
use App\Libraries\Query;
use App\Models\Appraisals\AppraisalPeriodOrganization as Mod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppraisalPeriodOrganization extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $params = [
            'user' => $user
        ];

        // if ($user->role->name !== 'DEVELOPER' && $user->role->name !== 'SUPERADMIN') {
        // $view = isMobile() ? '_front.aprraisal.organization.mobile' : '_front.aprraisal.organization.index';
        $view = isMobile() ? '_front.aprraisal.organization.mobile' : '_bak.appraisal.organization.main';
        return view($view, $params);
        // } else {
        // return view('_bak.appraisal.organization.main', $params);
        // }
    }

    public function data(Request $request, $counter = true)
    {
        $query = Mod::with(['organization', 'appraisal_period', 'appraisal_question_template']);

        $result = Query::open($query, [], $counter);

        return $result;
    }

    public function get(Request $request, $id = null)
    {
        return Mod::with(['organization', 'appraisal_period', 'appraisal_question_template'])->where('id', $id)->first();
    }

    public function setTemplate(Request $request)
    {
        try {
            $request->validate([
                'period' => 'required|integer|exists:iq_appraisal_period,id',
                'template' => 'required|integer|exists:iq_appraisal_question_template,id',
                'organization' => 'required|integer|exists:iq_org,id',
            ]);

            $period_id = $request->input('period');
            $template_id = $request->input('template');
            $organization_id = $request->input('organization');

            DB::beginTransaction();

            Mod::where('period_id', $period_id)
                ->where('organization_id', $organization_id)
                ->delete();

            Mod::create([
                'period_id' => $period_id,
                'template_id' => $template_id,
                'organization_id' => $organization_id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Template updated successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error updating template',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
