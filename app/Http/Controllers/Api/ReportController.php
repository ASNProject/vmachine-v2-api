<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Device;
use App\Models\Customer;
use App\Exports\TransactionsExport;
use App\Exports\ProductsExport;
use App\Exports\DevicesExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class ReportController extends Controller
{
    public function transactions(Request $request)
    {
        $query = Transaction::query()
            ->with(['customer', 'product', 'device']);

        if ($request->start && $request->end) {
            $query->whereBetween('created_at', [
                $request->start . ' 00:00:00',
                $request->end . ' 23:59:59'
            ]);
        }

        if ($request->device_id) {
            $query->where('device_id', $request->device_id);
        }

        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        $data = $query->latest()->get();

        return response()->json([
            'success' => true,
            'total_transactions' => $data->count(),
            'data' => $data
        ]);
    }

    public function exportTransactions(Request $request)
    {
        return Excel::download(
            new TransactionsExport($request),
            'transactions_report.xlsx'
        );
    }

    public function topProducts(Request $request)
    {
        $query = DB::table('transactions')
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                DB::raw('COUNT(transactions.id) as total_taken')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_taken');

        if ($request->start && $request->end) {
            $query->whereBetween('transactions.created_at', [
                $request->start . ' 00:00:00',
                $request->end . ' 23:59:59'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $query->get()
        ]);
    }

    public function exportProducts(Request $request)
    {
        return Excel::download(
            new ProductsExport($request),
            'products_report.xlsx'
        );
    }

    public function deviceUsage(Request $request)
    {
        $query = DB::table('transactions')
            ->join('devices', 'transactions.device_id', '=', 'devices.id')
            ->select(
                'devices.id',
                'devices.name',
                DB::raw('COUNT(transactions.id) as total_transactions')
            )
            ->groupBy('devices.id', 'devices.name')
            ->orderByDesc('total_transactions');

        return response()->json([
            'success' => true,
            'data' => $query->get()
        ]);
    }

    public function exportDevices(Request $request)
    {
        return Excel::download(
            new DevicesExport($request),
            'devices_report.xlsx'
        );
    }

    public function truncateTransactions()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('transactions')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return response()->json([
                'success' => true,
                'message' => 'Semua data transaksi berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
