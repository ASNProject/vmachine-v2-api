<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * index 
     * 
     * @return void
     */
    public function index()
    {
        // get all data
        $product = Product::latest()->paginate(10);

        return new Resource(true, 'List Data Items', $product);
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
            'product_name'         => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::create([
            'product_name'  => $request->product_name,
            'keypad'        => $request->keypad,
            'description'   => $request->description,
        ]);

        return new Resource(true, 'Data Product Berhasil Ditambahkan', $product);
    }
}
