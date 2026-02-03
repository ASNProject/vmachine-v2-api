<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Configuration;
use App\Models\Transaction;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * index 
     * 
     * @return void
     */
    public function index(Request $request)
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
            'uid'          => 'required|unique:customers,uid',
            'name'         => 'required',
            'role_id'      => 'required',
            'limits'        => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customer = Customer::create([
            'uid'           => $request->uid,
            'name'          => $request->name,
            'phone_number'  => $request->phone_number,
            'role_id'       => $request->role_id,
            'limits'        => $request->limits,      
        ]);

        return new Resource(true, 'Data Customer Berhasil Ditambahkan', $customer);
    }

    public function update(Request $request, $uid)
    {
        $validator = Validator::make($request->all(), [
            'uid' => [
                'required',
                Rule::unique('customers', 'uid')->ignore($uid, 'uid'),
            ],
            'name' => 'required',
            'role_id' => 'required|exists:roles,id',
            'limits' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customer = Customer::where('uid', $uid)->firstOrFail();

        $customer->update([
            'uid'          => $request->uid,
            'name'         => $request->name,
            'phone_number' => $request->phone_number,
            'role_id'      => $request->role_id,
            'limits'       => $request->limits,
        ]);

        return new Resource(true, 'Data Customer Berhasil Diperbarui', $customer);
    }

    /**
     * destroy
     * 
     * @param string $uid
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return new Resource(true, 'Data Customer Berhasil Dihapus', null);
    }

    public function show($uid)
    {
        $customer = Customer::with('role')
            ->where('uid', $uid)
            ->firstOrFail();

        $limitConfig   = Configuration::where('name', 'limit_time')->first();
        $isLimitActive = $limitConfig ? (bool) $limitConfig->status : false;

        $cooldown = [
            'active' => false,
            'remaining_seconds' => 0,
        ];

        if ($isLimitActive) {
            $lastTransaction = Transaction::where('uid', $customer->uid)
                ->latest()
                ->first();

            if ($lastTransaction) {
                $cooldownEnd = $lastTransaction->created_at->addSeconds(60);

                if ($cooldownEnd->isFuture()) {
                    $cooldown['active'] = true;
                    $cooldown['remaining_seconds'] = now()->diffInSeconds($cooldownEnd);
                }
            }
        }

        $customer->cooldown = $cooldown;

        return new Resource(true, 'Detail Customer', $customer);
    }


}
