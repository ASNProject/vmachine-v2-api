<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Group;
use App\Models\Product;
use App\Models\Transaction;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Configuration;

class TransactionController extends Controller
{
    public function transaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid'               => 'required|exists:customers,uid',
            'device_name'       => 'required|exists:devices,device_name',
            'group_id'          => 'required|exists:groups,id',
            'product_id'        => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customer = Customer::findOrFail($request->uid);
        $device = Device::where('device_name', $request->device_name)->firstOrFail();
        $group = Group::findOrFail($request->group_id);
        $product = Product::findOrFail($request->product_id);

        if ($group->device_id != $device->id) {
            return response()->json(['error' => 'Group does not belong to the specified device.'], 422);
        }

        if ($group->limits <= 0) {
            return response()->json(['error' => 'Group limit exceeded.'], 422);
        }

        $limitConfig = Configuration::where('name', 'limit_time')->first();
        $isLimitActive = $limitConfig ? (bool) $limitConfig->status : false;

        // Check last transaction for this customer and group
        if ($isLimitActive && $lastTransaction = Transaction::where('uid', $customer->uid)
                ->where('device_name', $device->device_name)
                ->where('group_id', $group->id)
                ->where('product_id', $product->id)
                ->orderBy('created_at', 'desc')
                ->first()
        ) {
            $diff = Carbon::now()->diffInSeconds($lastTransaction->created_at);
            if ($diff < 60) { // 60 detik cooldown
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction cooldown period not yet passed.'
                ], 429);
            }
        }

        $group->limits -= 1;
        $group->save();

        $transaction = Transaction::create([
            'uid'         => $customer->uid,
            'device_name' => $device->device_name,
            'group_id'    => $group->id,
            'product_id'  => $product->id,
        ]);

        return new Resource(true, 'Limit berhasil dikurangi', [
            'group'         => $group,
            'customer'      => $customer,
            'product'       => $product,
            'device_name'   => $device->device_name,
        ]);
    }
}
