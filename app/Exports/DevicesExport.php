<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DevicesExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = DB::table('transactions')
            ->select(
                'device_name',
                DB::raw('COUNT(id) as total_transactions')
            )
            ->groupBy('device_name')
            ->orderByDesc('total_transactions');

        // Optional filter tanggal
        if ($this->request->start && $this->request->end) {
            $query->whereBetween('created_at', [
                $this->request->start . ' 00:00:00',
                $this->request->end . ' 23:59:59'
            ]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Device Name',
            'Total Transactions'
        ];
    }
}