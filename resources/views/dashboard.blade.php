@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#4e3f9e,#6c5ce7)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div style="opacity:0.8;font-size:0.85rem">Data Barang</div>
                    <div style="font-size:2rem;font-weight:700">{{ $totalBarang }}</div>
                </div>
                <i class="fas fa-box fa-2x" style="opacity:0.5"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#00b894,#00cec9)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div style="opacity:0.8;font-size:0.85rem">Barang Masuk</div>
                    <div style="font-size:2rem;font-weight:700">{{ $totalBarangMasuk }}</div>
                </div>
                <i class="fas fa-sign-in-alt fa-2x" style="opacity:0.5"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#e17055,#d63031)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div style="opacity:0.8;font-size:0.85rem">Barang Keluar</div>
                    <div style="font-size:2rem;font-weight:700">{{ $totalBarangKeluar }}</div>
                </div>
                <i class="fas fa-sign-out-alt fa-2x" style="opacity:0.5"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#fdcb6e,#e67e22)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div style="opacity:0.8;font-size:0.85rem">Data User</div>
                    <div style="font-size:2rem;font-weight:700">{{ $totalUser }}</div>
                </div>
                <i class="fas fa-users fa-2x" style="opacity:0.5"></i>
            </div>
        </div>
    </div>
</div>

@if($stokMinimum->count() > 0)
<div class="card">
    <div class="card-header bg-white fw-semibold">
        <i class="fas fa-info-circle text-warning me-2"></i>
        Stok barang telah mencapai batas minimum
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Stok Minimum</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stokMinimum as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->kode_barang }}</td>
                    <td>{{ $item->name }}</td>
                    <td><span class="badge bg-danger">{{ $item->stock }}</span></td>
                    <td>{{ $item->stock_minimum }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection