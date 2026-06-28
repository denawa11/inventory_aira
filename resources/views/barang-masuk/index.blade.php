@extends('layouts.app')
@section('title', 'Barang Masuk')
@section('content')

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold" style="color:#4e3f9e"><i class="fas fa-sign-in-alt me-2"></i>Daftar Barang Masuk</span>
        <a href="{{ route('barang-masuk.create') }}" class="btn btn-sm text-white" style="background:#4e3f9e;border-radius:8px">
            <i class="fas fa-plus me-1"></i> Tambah
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0" style="font-size:0.875rem">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th class="text-center">Jumlah</th>
                    <th>Keterangan</th>
                    <th>Admin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangMasuk as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge" style="background:#d4edda;color:#155724;font-size:0.8rem;font-weight:600">
                            {{ $item->barang->kode_barang }}
                        </span>
                    </td>
                    <td class="fw-semibold">{{ $item->barang->name }}</td>
                    <td class="text-center">
                        <span class="badge bg-success px-3 py-2" style="font-size:0.85rem">
                            +{{ $item->jumlah }}
                        </span>
                    </td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                    <td>
                        @if($item->user)
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:28px;height:28px;border-radius:50%;background:#27ae60;color:white;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;flex-shrink:0">
                                    {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                </div>
                                <span style="font-size:0.85rem">{{ $item->user->name }}</span>
                            </div>
                        @else
                            <span class="text-muted" style="font-size:0.8rem">—</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('barang-masuk.destroy', $item) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm" style="background:#c0392b;color:white;border:none;border-radius:6px" onclick="return confirm('Yakin hapus? Stok akan dikurangi.')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        Belum ada data barang masuk
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $barangMasuk->links() }}</div>
</div>

@endsection