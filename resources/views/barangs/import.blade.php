@extends('layouts.app')
@section('title', 'Import Data Barang')
@section('content')

<style>
    .import-hero {
        background: linear-gradient(135deg, #4e3f9e 0%, #6c5ce7 50%, #a29bfe 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .import-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .import-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        right: 10%;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.07);
        border-radius: 50%;
    }
    .drop-zone {
        border: 2px dashed #a29bfe;
        border-radius: 16px;
        padding: 3rem 2rem;
        text-align: center;
        background: #f8f7ff;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }
    .drop-zone:hover, .drop-zone.dragover {
        border-color: #4e3f9e;
        background: #eeebff;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(78,63,158,0.12);
    }
    .drop-zone .drop-icon {
        font-size: 3.5rem;
        color: #a29bfe;
        margin-bottom: 1rem;
        display: block;
        transition: transform 0.3s ease;
    }
    .drop-zone:hover .drop-icon {
        transform: translateY(-4px);
        color: #4e3f9e;
    }
    .drop-zone input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }
    .file-preview {
        display: none;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        background: #f0eeff;
        border-radius: 12px;
        border: 1px solid #c9bfff;
        margin-top: 1rem;
    }
    .file-preview.show { display: flex; }
    .file-preview .file-icon { font-size: 2rem; color: #4e3f9e; }
    .file-preview .file-info { flex: 1; }
    .file-preview .file-name { font-weight: 600; color: #2d3436; font-size: 0.95rem; }
    .file-preview .file-size { font-size: 0.8rem; color: #636e72; }
    .btn-import {
        background: linear-gradient(135deg, #4e3f9e, #6c5ce7);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(78,63,158,0.3);
    }
    .btn-import:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(78,63,158,0.4);
        color: white;
    }
    .btn-import:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    .format-card {
        border-radius: 12px;
        border: 1px solid #e9ecef;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }
    .format-card .format-header {
        background: linear-gradient(135deg, #4e3f9e, #6c5ce7);
        color: white;
        padding: 0.85rem 1.25rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .col-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #4e3f9e, #6c5ce7);
        color: white;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        flex-shrink: 0;
    }
    .column-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.6rem 1rem;
        border-bottom: 1px solid #f1f0ff;
        transition: background 0.2s;
    }
    .column-item:last-child { border-bottom: none; }
    .column-item:hover { background: #f8f7ff; }
    .column-name { font-weight: 600; color: #4e3f9e; min-width: 80px; }
    .column-desc { font-size: 0.875rem; color: #636e72; }
    .alert-info-custom {
        background: #f0eeff;
        border: 1px solid #c9bfff;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        color: #4e3f9e;
        font-size: 0.875rem;
    }
    .loading-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }
    .loading-overlay.show { display: flex; }
    .loading-box {
        background: white;
        border-radius: 16px;
        padding: 2.5rem 3rem;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    .spinner-custom {
        width: 56px;
        height: 56px;
        border: 5px solid #f0eeff;
        border-top-color: #4e3f9e;
        border-radius: 50%;
        animation: spin 0.9s linear infinite;
        margin: 0 auto 1rem;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .download-btn {
        background: white;
        color: #4e3f9e;
        border: 2px solid rgba(255,255,255,0.5);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .download-btn:hover {
        background: rgba(255,255,255,0.15);
        color: white;
        border-color: white;
    }
</style>

{{-- Loading Overlay --}}
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-box">
        <div class="spinner-custom"></div>
        <p class="mb-0 fw-semibold text-dark">Sedang mengimport data...</p>
        <small class="text-muted">Mohon tunggu sebentar</small>
    </div>
</div>

{{-- Alert --}}
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 mb-3" role="alert"
    style="background:#fff0f0;color:#c0392b;border-left:4px solid #c0392b !important">
    <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 mb-3" role="alert"
    style="background:#fff0f0;color:#c0392b;border-left:4px solid #c0392b !important">
    <i class="fas fa-exclamation-triangle me-2"></i>
    @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
    @endforeach
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Hero --}}
<div class="import-hero">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div style="position:relative;z-index:1">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="fas fa-file-excel fa-lg"></i>
                <h4 class="mb-0 fw-bold">Import Data Barang</h4>
            </div>
            <p class="mb-0 opacity-75" style="font-size:0.9rem">Upload file Excel untuk menambah data barang secara massal</p>
        </div>
        <div class="d-flex gap-2 flex-wrap" style="position:relative;z-index:1">
            <a href="{{ route('barangs.index') }}" class="download-btn">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('barangs.import.template') }}" class="download-btn">
                <i class="fas fa-download"></i> Unduh Template
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Upload Form --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:#4e3f9e">
                    <i class="fas fa-upload me-2"></i>Upload File Excel
                </h6>

                <form action="{{ route('barangs.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                    @csrf

                    {{-- Drop Zone --}}
                    <div class="drop-zone" id="dropZone">
                        <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv">
                        <span class="drop-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </span>
                        <div class="fw-semibold mb-1" style="color:#4e3f9e;font-size:1.05rem">
                            Klik atau seret file ke sini
                        </div>
                        <small class="text-muted">Format: .xlsx, .xls, .csv &bull; Maksimal 5MB</small>
                    </div>

                    {{-- File Preview --}}
                    <div class="file-preview" id="filePreview">
                        <div class="file-icon"><i class="fas fa-file-excel"></i></div>
                        <div class="file-info">
                            <div class="file-name" id="fileName">-</div>
                            <div class="file-size" id="fileSize">-</div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" id="removeFile">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="d-flex align-items-center gap-3 mt-4">
                        <button type="submit" class="btn btn-import" id="submitBtn" disabled>
                            <i class="fas fa-file-import me-2"></i>Import Sekarang
                        </button>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1" style="color:#a29bfe"></i>
                            Data yang sudah ada akan diperbarui
                        </small>
                    </div>
                </form>
            </div>
        </div>

        {{-- Info --}}
        <div class="alert-info-custom mt-3 d-flex gap-2">
            <i class="fas fa-lightbulb mt-1 flex-shrink-0"></i>
            <div>
                <strong>Catatan penting:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    <li>Data dimulai dari <strong>baris ke-2</strong> (baris pertama adalah header)</li>
                    <li>Jika data dengan <strong>nama + merk + colour number</strong> yang sama sudah ada, data tersebut akan <strong>diperbarui</strong></li>
                    <li>Kode barang akan dibuat otomatis jika belum ada</li>
                    <li>Kolom <strong>In</strong> dan <strong>Out</strong> digunakan sebagai referensi; nilai <strong>Stock</strong> yang dipakai sebagai stok akhir</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Format Kolom --}}
    <div class="col-lg-5">
        <div class="format-card">
            <div class="format-header">
                <i class="fas fa-table"></i>
                Format Kolom Excel
            </div>
            <div class="p-0">
                @php
                    $columns = [
                        ['A', 'Name', 'Nama barang (wajib diisi)'],
                        ['B', 'Number', 'Colour number / kode warna'],
                        ['C', 'Merk', 'Merek / brand barang'],
                        ['D', 'Colour', 'Warna barang'],
                        ['E', 'In', 'Jumlah barang masuk'],
                        ['F', 'Out', 'Jumlah barang keluar (referensi)'],
                        ['G', 'Stock', 'Jumlah stok akhir'],
                        ['H', 'Keterangan', 'Catatan tambahan (opsional)'],
                    ];
                @endphp
                @foreach($columns as $col)
                <div class="column-item">
                    <span class="col-badge">{{ $col[0] }}</span>
                    <span class="column-name">{{ $col[1] }}</span>
                    <span class="column-desc">{{ $col[2] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
const fileInput = document.getElementById('fileInput');
const dropZone = document.getElementById('dropZone');
const filePreview = document.getElementById('filePreview');
const fileName = document.getElementById('fileName');
const fileSize = document.getElementById('fileSize');
const submitBtn = document.getElementById('submitBtn');
const removeFile = document.getElementById('removeFile');
const importForm = document.getElementById('importForm');
const loadingOverlay = document.getElementById('loadingOverlay');

function formatBytes(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

function showFile(file) {
    fileName.textContent = file.name;
    fileSize.textContent = formatBytes(file.size);
    filePreview.classList.add('show');
    submitBtn.disabled = false;
}

function clearFile() {
    fileInput.value = '';
    filePreview.classList.remove('show');
    submitBtn.disabled = true;
}

fileInput.addEventListener('change', function () {
    if (this.files.length > 0) showFile(this.files[0]);
});

removeFile.addEventListener('click', clearFile);

// Drag & Drop
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('dragover');
});
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('dragover');
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const dt = new DataTransfer();
        dt.items.add(files[0]);
        fileInput.files = dt.files;
        showFile(files[0]);
    }
});

// Show loading on submit
importForm.addEventListener('submit', function () {
    if (!submitBtn.disabled) {
        loadingOverlay.classList.add('show');
    }
});
</script>

@endsection
