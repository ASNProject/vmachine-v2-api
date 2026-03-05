<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Group;
use App\Models\Configuration;
use App\Models\Transaction;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CustomerImport;
use App\Exports\CustomerTemplateExport;

class CustomerController extends Controller
{
    /**
     * index 
     * 
     * @return void
     */
    public function index(Request $request)
    {
        // get all data
        $customers = Customer::with('role')->latest()->paginate(10);

        return new Resource(true, 'List Data Customers', $customers);
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
            'uid'          => 'required|unique:customers,uid',
            'name'         => 'required',
            'role_id'      => 'required',
            'limits'       => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Versi 1: Limit setiap group menggunakan default
        // $groups = Groups::all();
        // $limitGroupDevice = $groups->map(function ($group) {
        //     return [
        //         'device_id' => $group->device_id,
        //         'group_id'  => $group->id,
        //         'limit'     => 20,
        //     ];
        // })->values()->toArray();

        // Versi 2: Dibagi dari limits
        $groups = Group::all();
        $groupCount = $groups->count();

        if ($groupCount == 0) {
            return response()->json([
                'message' => 'No groups available in system'
            ], 422);
        }

        $totalLimit   = (int) $request->limits;
        $limitPerGroup = intdiv($totalLimit, $groupCount); 
        $remainder     = $totalLimit % $groupCount;       

        $limitGroupDevice = $groups->values()->map(function ($group, $index) 
            use ($limitPerGroup, $remainder, $groupCount) {

            return [
                'device_id' => $group->device_id,
                'group_id'  => $group->id,
                'limit'     => $index === ($groupCount - 1)
                                ? $limitPerGroup + $remainder
                                : $limitPerGroup,
            ];
        })->toArray();

        $customer = Customer::create([
            'uid'           => $request->uid,
            'name'          => $request->name,
            'phone_number'  => $request->phone_number,
            'role_id'       => $request->role_id,
            'limits'        => $request->limits,     
            'limit_group_device' => $limitGroupDevice, 
        ]);

        return new Resource(true, 'Data Customer Berhasil Ditambahkan', $customer);
    }

    public function update(Request $request, $uid)
    {
        $validator = Validator::make($request->all(), [
            'uid' => [
                'required',
                Rule::unique('customers', 'uid')->ignore($uid, 'uid'),
            ],
            'name' => 'required',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customer = Customer::where('uid', $uid)->firstOrFail();

        $limitGroupDevice = $customer->limit_group_device;

        if (!is_null($request->limits)) {

            $groups = Group::all();
            $groupCount = $groups->count();

            if ($groupCount == 0) {
                return response()->json([
                    'message' => 'No groups available in system'
                ], 422);
            }

            $totalLimit = (int) $request->limits;
            $limitPerGroup = intdiv($totalLimit, $groupCount);
            $remainder = $totalLimit % $groupCount;

            $limitGroupDevice = $groups->values()->map(function ($group, $index)
                use ($limitPerGroup, $remainder, $groupCount) {
                    return [
                        'device_id' => $group->device_id,
                        'group_id' => $group->id,
                        'limit' => $index === ($groupCount - 1) ? $limitPerGroup + $remainder : $limitPerGroup,
                    ];
                }
            )->toArray();
        }

        $customer->update([
            'uid'          => $request->uid,
            'name'         => $request->name,
            'phone_number' => $request->phone_number,
            'role_id'      => $request->role_id,
            'limits'       => $request->limits,
            'limit_group_device' => $limitGroupDevice,
        ]);

        return new Resource(true, 'Data Customer Berhasil Diperbarui', $customer);
    }

    /**
     * destroy
     * 
     * @param string $uid
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return new Resource(true, 'Data Customer Berhasil Dihapus', null);
    }

    public function show($uid)
    {
        $customer = Customer::with('role')
            ->where('uid', $uid)
            ->firstOrFail();

        $limitConfig   = Configuration::where('name', 'limit_time')->first();
        $isLimitActive = $limitConfig ? (bool) $limitConfig->status : false;

        $cooldown = [
            'active' => false,
            'remaining_seconds' => 0,
        ];

        if ($isLimitActive) {
            $lastTransaction = Transaction::where('uid', $customer->uid)
                ->latest()
                ->first();

            if ($lastTransaction) {
                $cooldownEnd = $lastTransaction->created_at->addSeconds(60);

                if ($cooldownEnd->isFuture()) {
                    $cooldown['active'] = true;
                    $cooldown['remaining_seconds'] = now()->diffInSeconds($cooldownEnd);
                }
            }
        }

        $customer->cooldown = $cooldown;

        return new Resource(true, 'Detail Customer', $customer);
    }

    public function addLimitGroupDevice(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'device_id'     => 'required',
            'group_id'      => 'required',
            'limit'         => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $current = $customer->limit_group_device ?? [];

        foreach ($current as $item) {
            if (
                $item['device_id'] == $request->device_id &&
                $item['group_id'] == $request->group_id
            ) {
                return response()->json([
                    'message' => 'Device & Group combination already exists'
                ], 409);
            }
        }

        $current[] = [
            'device_id' => $request->device_id,
            'group_id'  => $request->group_id,
            'limit'     => $request->limit,
        ];

        $customer->update(['limit_group_device' => $current]);
        return new Resource(true, 'Add limit group device to customer successfully', $customer);
    }

    public function updateLimitGroupDevice(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|exists:devices,id',
            'group_id'  => 'required|exists:groups,id',
            'limit'     => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $current = $customer->limit_group_device ?? [];

        $found = false;

        foreach ($current as $index => $item) {

            if (
                $item['device_id'] == $request->device_id &&
                $item['group_id'] == $request->group_id
            ) {
                $current[$index]['limit'] = $request->limit;
                $found = true;
                break;
            }
        }

        if (!$found) {
            return response()->json([
                'message' => 'Device & Group combination not found'
            ], 404);
        }

        $customer->limit_group_device = $current;
        $customer->save();

        return new Resource(true, 'Limit updated successfully', $customer);
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        $import = new CustomerImport();

        Excel::import($import, $request->file('file'));

        return response()->json([
            'success' => true,
            'message' => 'Import selesai',
            'result' => [
                'inserted' => $import->inserted,
                'updated' => $import->updated,
                'skipped' => $import->skipped
            ]
        ]);
    }

    public function downloadTemplate()
    {
        return Excel::download(
            new CustomerTemplateExport,
            'customer_template.xlsx'
        );
    }

}
