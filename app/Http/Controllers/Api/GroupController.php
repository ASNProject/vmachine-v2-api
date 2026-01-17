<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Product;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    /**
     * index 
     * 
     * @return void
     */
    public function index()
    {
        // get all data
        $itemgroup = Group::latest()->paginate(10);

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
            'device_id'           => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $itemgroup = Group::create([
            'group_name'           => $request->group_name,
            'limits'              => $request->limits,
            'device_id'            => $request->device_id,
            'description'         => $request->description,

        ]);

        return new Resource(true, 'Data Item Group Berhasil Ditambahkan', $itemgroup);
    }

    public function addProduct(Request $request, Group $group)
    {
        $validator = Validator::make($request->all(), [
            'product_id'          => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::findOrFail($request->product_id);

        $products = $group->products ?? [];

        if (collect($products)->contains('id', $product->id)) {
            return response()->json(['message' => 'Product already added to the group.'], 409);
        }

        $products[] = [
            'product_id' => $product->id,
            'product_name' => $product->product_name,
            'keypad' => $product->keypad,
            'description' => $product->description,
        ];

        $group->update(['products' => $products]);

        return new Resource(true, 'Product added to group successfully.', $group);
    }
}
