<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = DB::table('transactions')
            ->leftJoin('customers', 'transactions.uid', '=', 'customers.uid')
            ->leftJoin('products', 'transactions.product_id', '=', 'products.id')
            ->select(
                'transactions.id',
                'customers.name as customer',
                'products.product_name as product',
                'transactions.device_name as device',
                'transactions.created_at'
            );

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
            'Transaction ID',
            'Customer Name',
            'Product',
            'Device',
            'Date'
        ];
    }
}