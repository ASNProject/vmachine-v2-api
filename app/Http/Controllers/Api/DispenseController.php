<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DispenseController extends Controller
{
    /**
     * store
     * 
     * @param mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        $request->validate([
            'uid' => 'required',
            'keypad_code' => 'required',
        ]);

        // Validasi user
        $customer = Customer::where('uid', $request->uid)->where('status', 1)->first();
        if (!$customer) {
            return response()->json(['message'=>'Customer tidak valid'], 403);
        }

        // Item for keypad
        $keypad = Keypad::where('keypad_code', $request->keypad_code)->first();
        if (!$keypad) {
            return response()->json(['message'=>'Keypad tidak terdaftar'], 404);
        }

        $item = Item::find($keypad->product_id);
        $groupId = $item->group_id;

        // Get limit active period
        $period = LimitPeriod::where([
            'uid' => $customer->uid,
            'group_id' => $groupId,
            'period_month' => now()->format('Y-m')
        ])->first();

        if (!$period || $period->remaining_qty <= 0) {
            return response()->json(['message'=>'Limit habis'], 403);
        }

        // Decrement limit
        $period->decrement('remaining_qty');

        // Log Usage
        UserItemUsage::create([
            'uid' => $user->uid,
            'item_id' => $item->id,
            'group_id' => $groupId,
            'keypad_code' => $request->keypad_code,
            'usage_time' => now()
        ]);

        return response()->json([
            'message' => 'Item berhasil ditambahkan',
            'item' => $item->name,
            'remaining_limit' => $period->remaining_qty
        ]);
    }
}
