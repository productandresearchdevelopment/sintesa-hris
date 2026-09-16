<?php

namespace App\Controllers\Front\Leaves;

use App\Http\Controllers\Controller;
use App\Libraries\Query;
use App\Models\GlobalData as Mod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Type extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $params = ['user' => $user];

        return view('_front.leave.type.main', $params);
    }

    public function data(Request $request)
    {
        $search = ['id', 'name', 'alias'];
        $query = Mod::where('group', 'leave_type');

        $result = Query::open($query, $search);
        $result['data'] = collect($result['data'])->map(function ($item) {
            $item->flag_reduce_balance = filter_var($item->property->flag_reduce_balance ?? false, FILTER_VALIDATE_BOOLEAN);
            return $item;
        });

        return response()->json([
            'data' => $result['data'],
            'count' => $result['count']
        ]);
    }

    public function get(Request $request, $id = null)
    {
        $data = Mod::where('id', $id)->first();

        if ($data) {
            $data->flag_reduce_balance = filter_var($data->property->flag_reduce_balance ?? false, FILTER_VALIDATE_BOOLEAN);
        }

        return $data;
    }

    public function push(Request $request, $id = null)
    {
        DB::beginTransaction();
        try {
            $flagReduceBalance = filter_var($request->input('flag_reduce_balance'), FILTER_VALIDATE_BOOLEAN);

            $property = (object) [
                'flag_reduce_balance' => $flagReduceBalance,
            ];

            $input = [
                'group' => 'leave_type',
                'name' => $request->input('name'),
                'alias' => $request->input('alias'),
                'color' => $request->input('color'),
                'description' => $request->input('description'),
                'property' => $property,
            ];

            if ($id) {
                $data = Mod::find($id);
                $data->update($input);
            } else {
                $data = Mod::create($input);
            }

            DB::commit();
            return ['success' => true, 'message' => 'Success'];
        } catch (Exception $error) {
            DB::rollBack();
            return ['success' => false, 'message' => '500 ' . $error->getMessage()];
        }
    }

    public function delete(Request $request)
    {
        if ($data = json_decode($request->data)) {
            foreach ($data as $id) {
                if ($rec = Mod::where('id', $id)->first()) {
                    $leaves = $rec->leaves;

                    if (count($leaves) > 0) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed remove, this type has related data!'
                        ], 422);
                    }

                    $rec->delete();
                }
            }
            return ['success' => true, 'message' => 'Success!'];
        }
        return ['success' => false, 'message' => 'No Data!'];
    }
}
