@extends('layouts.app')
@section('title', 'Data Barang')
@section('content')



<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center rounded-4 py-3 gap-3">
        <span class="fw-semibold" style="color:#4e3f9e"><i class="fas fa-boxes me-2"></i>Daftar Barang</span>
        
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <form action="{{ route('barangs.index') }}" method="GET" class="d-flex m-0 position-relative" id="searchForm">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" id="searchInput" class="form-control" placeholder="Cari kode/nama..." value="{{ request('search') }}" autocomplete="off">
                    <button class="btn text-white" type="submit" style="background:#4e3f9e"><i class="fas fa-search"></i></button>
                </div>
                <div id="searchResults" class="dropdown-menu w-100 shadow-sm mt-1" style="display:none; position:absolute; top:100%; left:0; z-index:1000; border-radius:8px; border:1px solid #e0e0e0; max-height:200px; overflow-y:auto; padding:0;">
                </div>
            </form>
            <a href="{{ route('barangs.import.form') }}" class="btn btn-sm text-white" style="background:#6c5ce7;border-radius:8px">
                <i class="fas fa-file-import me-1"></i> Import Excel
            </a>
            <a href="{{ route('barangs.create') }}" class="btn btn-sm text-white" style="background:#4e3f9e;border-radius:8px">
                <i class="fas fa-plus me-1"></i> Tambah Barang
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0" style="font-size:0.875rem">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Colour Number</th>
                    <th>Merk</th>
                    <th>Colour</th>
                    <th class="text-center">Opened</th>
                    <th class="text-center" style="color:#27ae60">In</th>
                    <th class="text-center" style="color:#e74c7c">Out</th>
                    <th class="text-center">Stock</th>
                    <th>Stock Min</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $barang)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><span class="badge" style="background:#f0eeff;color:#4e3f9e;font-weight:600;font-size:0.8rem">{{ $barang->kode_barang }}</span></td>
                    <td class="fw-semibold">{{ $barang->name }}</td>
                    <td>{{ $barang->colour_number }}</td>
                    <td>{{ $barang->merk }}</td>
                    <td>{{ $barang->colour }}</td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <form action="{{ route('barangs.decrement-opened', $barang) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm" style="background:#e74c7c;color:white;border:none;border-radius:6px;width:24px;height:24px;padding:0;font-size:0.75rem">-</button>
                            </form>
                            <span class="badge" style="background:#4e3f9e;font-size:0.8rem;padding:4px 8px;border-radius:6px">
                                {{ $barang->opened }}
                            </span>
                            <form action="{{ route('barangs.increment-opened', $barang) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm" style="background:#6c5ce7;color:white;border:none;border-radius:6px;width:24px;height:24px;padding:0;font-size:0.75rem">+</button>
                            </form>
                        </div>
                    </td>
                    {{-- IN --}}
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <form action="{{ route('barangs.increment', $barang) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm" style="background:#27ae60;color:white;border:none;border-radius:6px;width:24px;height:24px;padding:0;font-size:0.75rem" title="Tambah stok masuk">+</button>
                            </form>
                            <span class="badge" style="background:#d4edda;color:#155724;font-size:0.8rem;padding:4px 8px;border-radius:6px">
                                {{ $barang->total_in }}
                            </span>
                        </div>
                    </td>
                    {{-- OUT --}}
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <span class="badge" style="background:#fde8e8;color:#c0392b;font-size:0.8rem;padding:4px 8px;border-radius:6px">
                                {{ $barang->total_out }}
                            </span>
                            <form action="{{ route('barangs.decrement', $barang) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm" style="background:#e74c7c;color:white;border:none;border-radius:6px;width:24px;height:24px;padding:0;font-size:0.75rem" title="Kurangi stok (catat keluar)">-</button>
                            </form>
                        </div>
                    </td>
                    {{-- STOCK --}}
                    <td class="text-center">
                        <span class="badge" style="background:{{ $barang->stock <= $barang->stock_minimum ? '#e74c7c' : '#4e3f9e' }};font-size:0.85rem;padding:6px 10px;border-radius:8px">
                            {{ $barang->stock }}
                        </span>
                        @if($barang->stock <= $barang->stock_minimum)
                            <br><small class="text-danger" style="font-size:0.7rem"><i class="fas fa-exclamation-triangle"></i> Min. stock</small>
                        @endif
                    </td>
                    <td>{{ $barang->stock_minimum }}</td>
                    <td>{{ $barang->keterangan ?? '-' }}</td>
                    <td>
                        <a href="{{ route('barangs.edit', $barang) }}" class="btn btn-sm me-1" style="background:#f39c12;color:white;border:none;border-radius:6px">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('barangs.destroy', $barang) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm" style="background:#c0392b;color:white;border:none;border-radius:6px" onclick="return confirm('Yakin hapus {{ $barang->name }}?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="13" class="text-center py-4 text-muted">
                        <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                        Belum ada data barang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($barangs->hasPages())
    <div class="card-footer bg-white rounded-bottom-4">{{ $barangs->links() }}</div>
    @endif
</div>

@push('scripts')
<script>
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    let debounceTimer;

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value.trim();

        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`/barangs/search-suggestions?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const a = document.createElement('a');
                            a.className = 'dropdown-item py-2 px-3 text-truncate';
                            a.style.fontSize = '0.85rem';
                            a.style.cursor = 'pointer';
                            a.innerHTML = `<span class="fw-bold" style="color:#4e3f9e">${item.name}</span> <span class="text-muted" style="font-size:0.75rem">(${item.merk} - ${item.colour_number})</span>`;
                            a.addEventListener('click', () => {
                                searchInput.value = item.name;
                                document.getElementById('searchForm').submit();
                            });
                            searchResults.appendChild(a);
                        });
                        searchResults.style.display = 'block';
                    } else {
                        searchResults.innerHTML = '<div class="px-3 py-2 text-muted" style="font-size:0.8rem">Tidak ada hasil</div>';
                        searchResults.style.display = 'block';
                    }
                })
                .catch(err => console.error(err));
        }, 300);
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
</script>
@endpush

@endsection