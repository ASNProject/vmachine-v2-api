<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ItemGroups;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;

class ItemGroupController extends Controller
{
    /**
     * index 
     * 
     * @return void
     */
    public function index()
    {
        // get all data
        $itemgroup = ItemGroups::latest()->paginate(10);

        return new Resource(true, 'List Data Item Group', $itemgroup);
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
            'group_name'          => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $itemgroup = ItemGroups::create([
            'group_name'           => $request->group_name,
            'description'         => $request->description,

        ]);

        return new Resource(true, 'Data Item Group Berhasil Ditambahkan', $itemgroup);
    }
}
