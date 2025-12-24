@extends('layout.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($menu)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data Menu yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $menu->id }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $menu->category->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Nama Menu</th>
                        <td>{{ $menu->name }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $menu->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Perlu Level Pedas?</th>
                        <td>{{ $menu->has_level ? 'Ya' : 'Tidak' }}</td>
                    </tr>
                    {{-- BARU: Tampilkan Foto Menu --}}
                    <tr>
                        <th>Foto Menu</th>
                        <td>
                            @if($menu->image)
                                {{-- Gunakan asset('storage/...') --}}
                                <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" style="max-width: 250px;">
                            @else
                                - (Belum ada foto)
                            @endif
                        </td>
                    </tr>
                </table>
            @endempty
            <a href="{{ url('admin/menus') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection