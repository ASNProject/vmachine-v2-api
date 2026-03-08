<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Group;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    /**
     * index 
     * 
     * @return void
     */
    public function index()
    {
        // get all data
        $devices = Device::latest()->paginate(10);
        // $devices = Device::with('groups)->latest()->paginate(10);

        $groups = Group::all();
        $devices->getCollection()->transform(function ($device) use ($groups) {
            $device->groups = $groups;
            return $device;
        });

        return new Resource(true, 'List Data Device', $devices);
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
            'device_name'         => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $device = Device::create([
            'device_name'   => $request->device_name,
        ]);

        return new Resource(true, 'Data Device Berhasil Ditambahkan', $device);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'device_name'         => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $device = Device::where('id', $id)->firstOrFail();

        $device->update([
            'device_name'   => $request->device_name,
        ]);

        return new Resource(true, 'Data Customer Berhasil Diperbarui', $device);
    }

    public function destroy($id)
    {
        $device = Device::find($id);

        $device->delete();
        return new Resource(true, 'Data berhasil dihapus', null);
    }

    public function show($id)
    {
        $device = Device::findOrFail($id);

        $groups = Group::all();

        $device->groups = $groups;

        return new Resource(true, 'Detail Device', $device);
    }


}
