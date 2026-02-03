<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;


class RoleController extends Controller
{
    /**
     * index 
     * 
     * @return void
     */
    public function index()
    {
        // get all data
        $roles = Role::latest()->paginate(10);

        return new Resource(true, 'List Data Roles', $roles);
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

        $role = Role::create([
            'name'          => $request->name,
            'description'   => $request->description,
        ]);

        return new Resource(true, 'Data Role Berhasil Ditambahkan', $role);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = Role::where('id', $id)->firstOrFail();

        $role->update([
            'name'          => $request->name,
            'description'   => $request->description,
        ]);

        return new Resource(true, 'Data Customer Berhasil Diperbarui', $role);
    }
    public function destroy($id)
    {
        $role = Role::find($id);

        $role->delete();
        return new Resource(true, 'Data berhasil dihapus', null);
    }
    public function show($id)
    {
        $role = Role::where('id', $id)->firstOrFail();

        return new Resource(true, 'Detail Role', $role);
    }
}
