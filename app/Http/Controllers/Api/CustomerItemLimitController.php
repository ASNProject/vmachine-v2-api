<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerItemLimits;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;

class CustomerItemLimitController extends Controller
{
    /**
     * index
     * 
     * @return void
     */
    public function index()
    {
        // get all data
        $cils =  CustomerItemLimits::with(['group', 'customer'])->latest()->paginate(10);

        return new Resource(true, 'List Data Customer Item Limit', $cils);
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
            'limit_qty'         => 'required',
            'limit_time_type'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customerLimit = CustomerItemLimits::create([
            'uid'               => $request->uid,
            'group_id'          => $request->group_id,
            'limit_qty'         => $request->limit_qty,
            'limit_time_type'   => $request->limit_time_type,    
        ]);

        return new Resource(true, 'Data Customer Item Limit Berhasil Ditambahkan', $customerLimit);
    }
}
