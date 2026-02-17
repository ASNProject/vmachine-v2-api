<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        return DB::table('transactions')
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->select(
                'products.product_name as product_name',
                DB::raw('COUNT(transactions.id) as total_taken')
            )
            ->groupBy('products.product_name')
            ->orderByDesc('total_taken')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Total Taken'
        ];
    }
}