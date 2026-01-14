<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keypads;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;

class KeypadController extends Controller
{
    /**
     * index 
     * 
     * @return void
     */
    public function index()
    {
        // get all data
        $keypads = Keypads::with('item')->latest()->paginate(10);

        return new Resource(true, 'List Data Keypads', $keypads);
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
            'keypad_code'  => 'required',
            'item_id'      => 'required',
        ]); 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $keypad = Keypads::create([
            'keypad_code'  => $request->keypad_code,
            'item_id'      => $request->item_id,  
        ]);
        return new Resource(true, 'Data Keypad Berhasil Ditambahkan', $keypad);
    }
}
