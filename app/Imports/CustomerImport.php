<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CustomerImport implements ToCollection
{
    public $inserted = 0;
    public $updated = 0;
    public $skipped = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows->skip(1) as $row) {

            // pastikan selalu string
            $uid = trim((string) ($row[0] ?? ''));
            $name = trim((string) ($row[1] ?? ''));

            // nomor telepon tetap string
            $phone = trim((string) ($row[2] ?? ''));

            // hanya ambil angka
            $phone = preg_replace('/[^0-9]/', '', $phone);

            // jika excel menghapus 0 depan
            if ($phone && !str_starts_with($phone, '0')) {
                $phone = '0' . $phone;
            }

            $role_id = $row[3] ?? null;
            $limits = (int) ($row[4] ?? 0);

            $group1 = (int) ($row[5] ?? 0);
            $group2 = (int) ($row[6] ?? 0);
            $group3 = (int) ($row[7] ?? 0);
            $group4 = (int) ($row[8] ?? 0);
            $group5 = (int) ($row[9] ?? 0);

            if (!$uid || !$name || !$role_id) {
                $this->skipped++;
                continue;
            }

            $limitGroupDevice = [
                ['group_id' => 1, 'limit' => $group1],
                ['group_id' => 2, 'limit' => $group2],
                ['group_id' => 3, 'limit' => $group3],
                ['group_id' => 4, 'limit' => $group4],
                ['group_id' => 5, 'limit' => $group5],
            ];

            $customer = Customer::where('uid', $uid)->first();

            if ($customer) {

                $customer->update([
                    'name' => $name,
                    'phone_number' => $phone,
                    'role_id' => $role_id,
                    'limits' => $limits,
                    'limit_group_device' => $limitGroupDevice
                ]);

                $this->updated++;

            } else {

                Customer::create([
                    'uid' => $uid,
                    'name' => $name,
                    'phone_number' => $phone,
                    'role_id' => $role_id,
                    'limits' => $limits,
                    'limit_group_device' => $limitGroupDevice
                ]);

                $this->inserted++;
            }
        }
    }
}