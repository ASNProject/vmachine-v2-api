<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LimitPeriods;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;

class LimitPeriodController extends Controller
{
    /**
     * index 
     * 
     * @return void
     */
    public function index()
    {
        // get all data
        $limitp = LimitPeriods::with(['group', 'customer'])->latest()->paginate(10);

        return new Resource(true, 'List Data Limit Periods', $limitp);
    }

    /**
     * store
     * 
     * @param mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid'               => 'required',
            'group_id'          => 'required',
            'period_month'      => 'required',
            'remaining_qty'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $limitPeriod = LimitPeriods::create([
            'uid'               => $request->uid,
            'group_id'          => $request->group_id,
            'period_month'      => $request->period_month,
            'remaining_qty'      => $request->remaining_qty,      
        ]);

        return new Resource(true, 'Data Limit Period Berhasil Ditambahkan', $limitPeriod);
    }
}
