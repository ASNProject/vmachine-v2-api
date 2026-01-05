<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * index 
     * 
     * @return void
     */
    public function index()
    {
        // get all data
        $customers = Customer::with('role')->latest()->paginate(10);

        return new Resource(true, 'List Data Customers', $customers);
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
            'name'         => 'required',
            'role_id'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customer = Customer::create([
            'uid'           => $request->uid,
            'name'          => $request->name,
            'phone_number'  => $request->phone_number,
            'role_id'       => $request->role_id,      
        ]);

        return new Resource(true, 'Data Customer Berhasil Ditambahkan', $customer);
    }
}
