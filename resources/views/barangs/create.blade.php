@extends('layouts.app')
@section('title', 'Tambah Barang')
@section('content')

<div class="card">
    <div class="card-header bg-white fw-semibold">Tambah Barang</div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)<p class="mb-0">{{ $error }}</p>@endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('barangs.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kode Barang</label>
                    <input type="text" name="kode_barang" class="form-control" value="{{ old('kode_barang') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Colour Number</label>
                    <input type="text" name="colour_number" class="form-control" value="{{ old('colour_number') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Merk</label>
                    <input type="text" name="merk" class="form-control" value="{{ old('merk') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Colour</label>
                    <input type="text" name="colour" class="form-control" value="{{ old('colour') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Stock Minimum</label>
                    <input type="number" name="stock_minimum" class="form-control" value="{{ old('stock_minimum', 0) }}">
                </div>
                <div class="col-md-12">
                    <div class="form-check">
                        <input type="checkbox" name="opened" value="1" class="form-check-input" {{ old('opened') ? 'checked' : '' }}>
                        <label class="form-check-label">Opened</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn text-white" style="background:#4e3f9e">Simpan</button>
                <a href="{{ route('barangs.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

@endsection