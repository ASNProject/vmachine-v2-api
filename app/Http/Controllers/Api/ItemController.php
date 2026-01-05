<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;


class ItemController extends Controller
{
    /**
     * index 
     * 
     * @return void
     */
    public function index()
    {
        // get all data
        $items = Item::latest()->paginate(10);

        return new Resource(true, 'List Data Items', $items);
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
            'name'         => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $item = Item::create([
            'name'          => $request->name,  
        ]);

        return new Resource(true, 'Data Item Berhasil Ditambahkan', $item);
    }
}
