@extends('layout.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($category)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan! Data Kategori tidak ditemukan.</h5>
                </div>
                <a href="{{ url('admin/categories') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
                <form method="POST" action="{{ url('/admin/categories/' . $category->id) }}" class="form-horizontal">
                    @csrf
                    {!! method_field('PUT') !!} <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Nama Kategori</label>
                        <div class="col-11">
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Deskripsi</label>
                        <div class="col-11">
                            <textarea class="form-control" id="description" name="description">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label"></label>
                        <div class="col-11">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            <a class="btn btn-sm btn-default ml-1" href="{{ url('admin/categories') }}">Kembali</a>
                        </div>
                    </div>
                </form>
            @endempty
        </div>
    </div>
@endsection