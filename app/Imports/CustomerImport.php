<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Group;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CustomerImport implements ToCollection
{
    public $inserted = 0;
    public $updated = 0;
    public $skipped = 0;

    public function collection(Collection $rows)
    {
        $groups = Group::all();
        $groupCount = $groups->count();

        if ($groupCount == 0) {
            return;
        }

        foreach ($rows->skip(1) as $row) {

            $uid = trim((string) ($row[0] ?? ''));
            $name = trim((string) ($row[1] ?? ''));

            $phone = trim((string) ($row[2] ?? ''));

            $phone = preg_replace('/[^0-9]/', '', $phone);

            $role_id = $row[3] ?? null;
            $limits = (int) ($row[4] ?? 0);

            if (!$uid || !$name || !$role_id || !$limits) {
                $this->skipped++;
                continue;
            }

            // $limitPerGroup = intdiv($limits, $groupCount);
            // $remainder = $limits % $groupCount;

            // $limitGroupDevice = $groups->values()->map(function ($group, $index)
            //     use ($limitPerGroup, $remainder, $groupCount) {

            //     return [
            //         'device_id' => $group->device_id,
            //         'group_id' => $group->id,
            //         'limit' => $index === ($groupCount - 1)
            //             ? $limitPerGroup + $remainder
            //             : $limitPerGroup,
            //     ];
            // })->toArray();

            $customer = Customer::where('uid', $uid)->first();

            if ($customer) {

                $customer->update([
                    'name' => $name,
                    'phone_number' => $phone,
                    'role_id' => $role_id,
                    'limits' => $limits,
                    // 'limit_group_device' => $limitGroupDevice
                ]);

                $this->updated++;

            } else {

                Customer::create([
                    'uid' => $uid,
                    'name' => $name,
                    'phone_number' => $phone,
                    'role_id' => $role_id,
                    'limits' => $limits,
                    // 'limit_group_device' => $limitGroupDevice
                ]);

                $this->inserted++;
            }
        }
    }
}