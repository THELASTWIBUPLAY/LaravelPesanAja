@extends('layout.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('admin/levels') }}" class="form-horizontal">
                @csrf

                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Nama Level</label>
                    <div class="col-11">
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Kode Level (Opsional)</label>
                    <div class="col-11">
                        <input type="text" class="form-control" id="code" name="code"
                            value="{{ old('code') }}">
                        @error('code')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                        <small class="form-text text-muted">Contoh: L1, S (Sedang), I (Immortality)</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Biaya Tambahan (Rp)</label>
                    <div class="col-11">
                        <input type="number" class="form-control" id="extra_cost" name="extra_cost"
                            value="{{ old('extra_cost', 0) }}" required min="0">
                        @error('extra_cost')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                        <small class="form-text text-muted">Biaya tambahan yang dikenakan (contoh: untuk Level pedas tertentu).</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Deskripsi</label>
                    <div class="col-11">
                        <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
                        @error('description')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-1 control-label col-form-label"></label>
                    <div class="col-11">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a class="btn btn-sm btn-default ml-1" href="{{ url('admin/levels') }}">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection