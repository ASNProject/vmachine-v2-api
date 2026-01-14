<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerItemUsage;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;

class CustomerItemUsageController extends Controller
{
    /**
     * index 
     * 
     * @return void
     */
    public function index()
    {
        // get all data
        $cis = CustomerItemUsage::with(['item', 'group', 'customer'])->latest()->paginate(10);

        return new Resource(true, 'List Data Customers Item Usage', $cis);
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
            'uid'          => 'required',
            'item_id'      => 'required',
            'group_id'     => 'required',
            'keypad_code'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customerItemUsage = CustomerItemUsage::create([
            'uid'               => $request->uid,
            'item_id'           => $request->item_id,
            'group_id'          => $request->group_id,
            'keypad_code'       => $request->keypad_code,      
        ]);

        return new Resource(true, 'Data Customer Item Usage Berhasil Ditambahkan', $customerItemUsage);
    }
}
