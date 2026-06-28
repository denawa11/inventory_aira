<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index()
    {
        $barangMasuk = BarangMasuk::with('barang')->latest()->paginate(10);
        return view('barang-masuk.index', compact('barangMasuk'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('barang-masuk.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id'  => 'required|exists:barangs,id',
            'jumlah'     => 'required|integer|min:1',
            'tanggal'    => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        BarangMasuk::create([
            'barang_id'  => $request->barang_id,
            'user_id'    => auth()->id(),
            'jumlah'     => $request->jumlah,
            'tanggal'    => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        // Update stock
        $barang = Barang::find($request->barang_id);
        $barang->increment('stock', $request->jumlah);
        $barang->increment('total_in', $request->jumlah);

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil dicatat!');
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        $barang = $barangMasuk->barang;
        $jumlah = $barangMasuk->jumlah;

        \Illuminate\Support\Facades\DB::transaction(function () use ($barang, $jumlah, $barangMasuk) {
            $barang->decrement('stock', $jumlah);
            $barang->decrement('total_in', $jumlah);
            $barangMasuk->delete();
        });

        return redirect()->route('barang-masuk.index')->with('success', 'Data berhasil dihapus & stok dikurangi!');
    }
}