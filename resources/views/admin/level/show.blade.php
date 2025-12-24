@extends('layout.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($level)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data Level yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $level->id }}</td>
                    </tr>
                    <tr>
                        <th>Nama Level</th>
                        <td>{{ $level->name }}</td>
                    </tr>
                    <tr>
                        <th>Kode Level</th>
                        <td>{{ $level->code ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Biaya Tambahan</th>
                        <td>Rp {{ number_format($level->extra_cost, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $level->description ?? '-' }}</td>
                    </tr>
                </table>
            @endempty
            <a href="{{ url('admin/levels') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection