<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Imports\BarangImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $barangs = Barang::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('colour_number', 'like', "%{$search}%")
                         ->orWhere('merk', 'like', "%{$search}%");
        })->latest()->paginate(50)->withQueryString();

        return view('barangs.index', compact('barangs', 'search'));
    }

    public function suggestions(Request $request)
    {
        $search = $request->query('q');
        if (!$search) {
            return response()->json([]);
        }

        $barangs = Barang::where('name', 'like', "%{$search}%")
            ->orWhere('colour_number', 'like', "%{$search}%")
            ->orWhere('merk', 'like', "%{$search}%")
            ->limit(10)
            ->get(['name', 'colour_number', 'merk']);

        return response()->json($barangs);
    }

    public function create()
    {
        return view('barangs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang'   => 'required|unique:barangs,kode_barang',
            'name'          => 'required|string|max:255',
            'colour_number' => 'required|string|max:100',
            'merk'          => 'required|string|max:255',
            'colour'        => 'required|string|max:255',
            'opened'        => 'required|integer|min:0',
            'stock'         => 'required|integer|min:0',
            'stock_minimum' => 'required|integer|min:0',
            'keterangan'    => 'nullable|string',
        ]);

        $barang = Barang::create([
            'kode_barang'   => $request->kode_barang,
            'name'          => $request->name,
            'colour_number' => $request->colour_number,
            'merk'          => $request->merk,
            'colour'        => $request->colour,
            'opened'        => $request->opened,
            'stock'         => $request->stock,
            'stock_minimum' => $request->stock_minimum,
            'keterangan'    => $request->keterangan,
        ]);

        return redirect()->route('barangs.index')->with('success', "Barang {$barang->name} berhasil ditambahkan!");
    }

    public function edit(Barang $barang)
    {
        return view('barangs.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode_barang'   => 'required|unique:barangs,kode_barang,' . $barang->id,
            'name'          => 'required|string|max:255',
            'colour_number' => 'required|string|max:100',
            'merk'          => 'required|string|max:255',
            'colour'        => 'required|string|max:255',
            'opened'        => 'required|integer|min:0',
            'stock'         => 'required|integer|min:0',
            'stock_minimum' => 'required|integer|min:0',
            'keterangan'    => 'nullable|string',
        ]);

        $barang->update([
            'kode_barang'   => $request->kode_barang,
            'name'          => $request->name,
            'colour_number' => $request->colour_number,
            'merk'          => $request->merk,
            'colour'        => $request->colour,
            'opened'        => $request->opened,
            'stock'         => $request->stock,
            'stock_minimum' => $request->stock_minimum,
            'keterangan'    => $request->keterangan,
        ]);

        return redirect()->route('barangs.index')->with('success', "Barang {$barang->name} berhasil diupdate!");
    }

    public function destroy(Barang $barang)
    {
        $name = $barang->name;
        $barang->delete();
        return redirect()->route('barangs.index')->with('success', "Barang {$name} berhasil dihapus!");
    }

    public function importForm()
    {
        return view('barangs.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'Pilih file Excel terlebih dahulu.',
            'file.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        try {
            $import = new BarangImport();
            Excel::import($import, $request->file('file'));

            $count = $import->getImportedCount();
            return redirect()->route('barangs.index')
                ->with('success', "Import berhasil! {$count} data barang telah diproses.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header row
        $headers = ['Name', 'Number', 'Merk', 'Colour', 'In', 'Out', 'Stock', 'Keterangan'];
        foreach ($headers as $i => $header) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $sheet->setCellValue($col . '1', $header);
            // Style header
            $sheet->getStyle($col . '1')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                           'startColor' => ['argb' => 'FF4E3F9E']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ]);
            $sheet->getColumnDimensionByColumn($i + 1)->setAutoSize(true);
        }

        // Sample data row
        $sampleData = [
            ['Cat Tembok', 'CT-001', 'Avian', 'Putih', 50, 10, 40, 'Stok awal'],
            ['Cat Kayu', 'CK-002', 'Glotex', 'Coklat', 30, 5, 25, ''],
        ];
        foreach ($sampleData as $rowIdx => $rowData) {
            foreach ($rowData as $colIdx => $value) {
                $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 1);
                $sheet->setCellValue($col . ($rowIdx + 2), $value);
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'template_import_barang.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function increment(Barang $barang)
    {
        DB::transaction(function () use ($barang) {
            $barang->increment('stock');
            $barang->increment('total_in');
            BarangMasuk::create([
                'barang_id'  => $barang->id,
                'user_id'    => auth()->id(),
                'jumlah'     => 1,
                'tanggal'    => now()->toDateString(),
                'keterangan' => 'Tambah stok dari halaman Data Barang',
            ]);
        });
        return redirect()->route('barangs.index')->with('success', "Stock {$barang->name} bertambah & tercatat!");
    }

    public function decrement(Barang $barang)
    {
        if ($barang->stock <= 0) {
            return redirect()->route('barangs.index')->with('error', "Stock {$barang->name} tidak bisa kurang dari 0!");
        }

        DB::transaction(function () use ($barang) {
            $barang->decrement('stock');
            $barang->increment('total_out');
            BarangKeluar::create([
                'barang_id'  => $barang->id,
                'user_id'    => auth()->id(),
                'jumlah'     => 1,
                'tanggal'    => now()->toDateString(),
                'keterangan' => 'Kurangi stok dari halaman Data Barang',
            ]);
        });
        return redirect()->route('barangs.index')->with('success', "Stock {$barang->name} berkurang & tercatat!");
    }

    public function incrementOpened(Barang $barang)
    {
        if ($barang->stock <= 0) {
            return redirect()->route('barangs.index')->with('error', "Stock {$barang->name} sudah habis, tidak bisa dibuka!");
        }

        DB::transaction(function () use ($barang) {
            $barang->increment('opened');
            $barang->decrement('stock');
            $barang->increment('total_out');
            BarangKeluar::create([
                'barang_id'  => $barang->id,
                'user_id'    => auth()->id(),
                'jumlah'     => 1,
                'tanggal'    => now()->toDateString(),
                'keterangan' => 'Dibuka dari halaman Data Barang',
            ]);
        });
        return redirect()->route('barangs.index')->with('success', "{$barang->name} berhasil dibuka & tercatat!");
    }

    public function decrementOpened(Barang $barang)
    {
        if ($barang->stock <= 0) {
            return redirect()->route('barangs.index')->with('error', "Stock {$barang->name} sudah habis!");
        }

        if ($barang->opened <= 0) {
            return redirect()->route('barangs.index')->with('error', "{$barang->name} belum pernah dibuka!");
        }

        DB::transaction(function () use ($barang) {
            $barang->decrement('opened');
            $barang->decrement('stock');
            $barang->increment('total_out');
            BarangKeluar::create([
                'barang_id'  => $barang->id,
                'user_id'    => auth()->id(),
                'jumlah'     => 1,
                'tanggal'    => now()->toDateString(),
                'keterangan' => 'Opened dikurangi dari halaman Data Barang',
            ]);
        });
        return redirect()->route('barangs.index')->with('success', "Opened {$barang->name} berkurang & tercatat!");
    }
}