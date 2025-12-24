@extends('layout.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($table)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan! Data Meja tidak ditemukan.</h5>
                </div>
                <a href="{{ url('admin/tables') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
                <form method="POST" action="{{ url('/admin/tables/' . $table->id) }}" class="form-horizontal">
                    @csrf
                    {!! method_field('PUT') !!}

                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Nomor Meja</label>
                        <div class="col-11">
                            <input type="text" class="form-control" id="table_number" name="table_number"
                                value="{{ old('table_number', $table->table_number) }}" required>
                            @error('table_number')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Status</label>
                        <div class="col-11">
                            <select class="form-control" id="status" name="status" required>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" 
                                        {{ old('status', $table->status) == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
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
            @endempty
        </div>
    </div>
@endsection