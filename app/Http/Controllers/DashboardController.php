<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang       = Barang::count();
        $totalBarangMasuk  = BarangMasuk::count();
        $totalBarangKeluar = BarangKeluar::count();
        $totalUser         = User::count();
        $stokMinimum       = Barang::where('stock', '<=', 'stock_minimum')->get();
        $stokMinimum       = Barang::whereColumn('stock', '<=', 'stock_minimum')->get();

        return view('dashboard', compact(
            'totalBarang', 'totalBarangMasuk',
            'totalBarangKeluar', 'totalUser', 'stokMinimum'
        ));
    }
}