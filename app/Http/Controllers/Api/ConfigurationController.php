<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;

class ConfigurationController extends Controller
{
    /**
     * index 
     * 
     * @return void
     */
    public function index()
    {
        // get all data
        $configuration = Configuration::latest()->paginate(10);

        return new Resource(true, 'List Data Configuration', $configuration);
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
            'status'       => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $configuration = Configuration::create([
            'name'          => $request->name,
            'status'        => $request->status,
        ]);

        return new Resource(true, 'Data Configuration Berhasil Ditambahkan', $configuration);
    }
}
