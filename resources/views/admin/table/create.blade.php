@extends('layout.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('admin/tables') }}" class="form-horizontal">
                @csrf

                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Nomor Meja</label>
                    <div class="col-11">
                        <input type="text" class="form-control" id="table_number" name="table_number"
                            value="{{ old('table_number') }}" required>
                        @error('table_number')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                        <small class="form-text text-muted">Contoh: T-01, VIP-A</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Status Awal</label>
                    <div class="col-11">
                        <select class="form-control" id="status" name="status" required>
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="cleaning" {{ old('status') == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                        </select>
                        @error('status')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-1 control-label col-form-label"></label>
                    <div class="col-11">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a class="btn btn-sm btn-default ml-1" href="{{ url('admin/tables') }}">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection