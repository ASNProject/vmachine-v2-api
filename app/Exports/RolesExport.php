<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RolesExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = DB::table('transactions')
            ->join('customers', 'transactions.uid', '=', 'customers.uid')
            ->join('roles', 'customers.role_id', '=', 'roles.id')
            ->select(
                'transactions.id',
                'transactions.uid',
                'transactions.device_name',
                'transactions.group_id',
                'transactions.product_id',
                'transactions.created_at',
                'roles.name as role_name'
            );

        if ($this->request->role_id) {
            $query->where('roles.id', $this->request->role_id);
        }

        if ($this->request->start && $this->request->end) {
            $query->whereBetween('transactions.created_at', [
                $this->request->start . ' 00:00:00',
                $this->request->end . ' 23:59:59'
            ]);
        }

        return $query->orderByDesc('transactions.created_at')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'UID',
            'Device Name',
            'Group ID',
            'Product ID',
            'Tanggal',
            'Role'
        ];
    }
}