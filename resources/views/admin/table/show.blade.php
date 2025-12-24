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
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data Meja yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $table->id }}</td>
                    </tr>
                    <tr>
                        <th>Nomor Meja</th>
                        <td>{{ $table->table_number }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @php
                                $badgeClass = match($table->status) {
                                    'available' => 'bg-success',
                                    'occupied' => 'bg-danger',
                                    'cleaning' => 'bg-warning',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ strtoupper($table->status) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $table->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Diperbarui Pada</th>
                        <td>{{ $table->updated_at }}</td>
                    </tr>
                </table>
            @endempty
            <a href="{{ url('admin/tables') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection