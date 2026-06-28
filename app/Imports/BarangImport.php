<?php

namespace App\Imports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class BarangImport implements ToModel, WithStartRow
{
    use Importable;

    private int $importedCount = 0;
    private int $skippedCount = 0;
    private array $errors = [];

    /**
     * Data starts from row 2 (row 1 is header)
     * Columns: Name, Number, Merk, Colour, In, Out, Stock, Keterangan
     */
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row): ?Barang
    {
        // Skip empty rows
        if (empty(array_filter($row))) {
            return null;
        }

        // Map columns (0-indexed):
        // 0 = Name, 1 = Number (colour_number), 2 = Merk, 3 = Colour,
        // 4 = In (stock masuk), 5 = Out (stock keluar), 6 = Stock, 7 = Keterangan
        $name          = trim($row[0] ?? '');
        $colourNumber  = trim($row[1] ?? '');
        $merk          = trim($row[2] ?? '');
        $colour        = trim($row[3] ?? '');
        $stockIn       = intval($row[4] ?? 0);
        // $stockOut   = intval($row[5] ?? 0); // Out - bisa digunakan untuk referensi
        $stock         = intval($row[6] ?? 0);
        $keterangan    = trim($row[7] ?? '');

        if (empty($name)) {
            $this->skippedCount++;
            return null;
        }

        // Generate kode_barang otomatis jika belum ada
        $kodeBarang = $this->generateKode($name, $merk);

        // Cek apakah sudah ada dengan nama + merk yang sama, jika ada update
        $existing = Barang::where('name', $name)
                          ->where('merk', $merk)
                          ->where('colour_number', $colourNumber)
                          ->first();

        if ($existing) {
            $existing->update([
                'colour'       => $colour ?: $existing->colour,
                'stock'        => $stock,
                'keterangan'   => $keterangan ?: $existing->keterangan,
            ]);
            $this->importedCount++;
            return null; // Return null to avoid duplicate insertion
        }

        $this->importedCount++;

        return new Barang([
            'kode_barang'   => $kodeBarang,
            'name'          => $name,
            'colour_number' => $colourNumber,
            'merk'          => $merk,
            'colour'        => $colour,
            'opened'        => 0,
            'stock'         => $stock,
            'stock_minimum' => 0,
            'keterangan'    => $keterangan,
        ]);
    }

    private function generateKode(string $name, string $merk): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3));
        $merkPrefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $merk), 0, 2));
        $timestamp = now()->format('dmHi');
        $kode = $prefix . $merkPrefix . $timestamp . rand(10, 99);

        // Ensure uniqueness
        while (Barang::where('kode_barang', $kode)->exists()) {
            $kode = $prefix . $merkPrefix . $timestamp . rand(100, 999);
        }

        return $kode;
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }
}
