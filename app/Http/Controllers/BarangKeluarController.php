<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $barangKeluar = BarangKeluar::with('barang')->latest()->paginate(10);
        return view('barang-keluar.index', compact('barangKeluar'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('barang-keluar.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id'  => 'required|exists:barangs,id',
            'jumlah'     => 'required|integer|min:1',
            'tanggal'    => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $barang = Barang::find($request->barang_id);

        if ($request->jumlah > $barang->stock) {
            return back()->withErrors(['jumlah' => 'Jumlah keluar melebihi stock yang tersedia!']);
        }

        BarangKeluar::create([
            'barang_id'  => $request->barang_id,
            'user_id'    => auth()->id(),
            'jumlah'     => $request->jumlah,
            'tanggal'    => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);
        $barang->decrement('stock', $request->jumlah);
        $barang->increment('total_out', $request->jumlah);

        return redirect()->route('barang-keluar.index')->with('success', 'Barang keluar berhasil dicatat!');
    }

    public function destroy(BarangKeluar $barangKeluar)
    {
        $barang = $barangKeluar->barang;
        $jumlah = $barangKeluar->jumlah;

        \Illuminate\Support\Facades\DB::transaction(function () use ($barang, $jumlah, $barangKeluar) {
            $barang->increment('stock', $jumlah);
            $barang->decrement('total_out', $jumlah);
            $barangKeluar->delete();
        });

        return redirect()->route('barang-keluar.index')->with('success', 'Data berhasil dihapus & stok dikembalikan!');
    }
}