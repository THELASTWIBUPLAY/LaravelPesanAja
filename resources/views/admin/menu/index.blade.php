@extends('layout.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('admin/menus/create') }}">Tambah Menu</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter: </label>
                        <div class="col-3">
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="">Semua Kategori</option>
                                {{-- $categories adalah variabel yang dikirim dari MenuController::index() --}}
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Kategori Menu</small>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover table-sm" id="table_menu">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th> {{-- BARU --}}
                        <th>Kategori</th>
                        <th>Nama Menu</th>
                        <th>Harga</th>
                        <th>Perlu Level</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('css')
    {{-- Tambahkan CSS spesifik jika ada --}}
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            // Perubahan ID dari table_user menjadi table_menu
            var dataMenu = $('#table_menu').DataTable({
                serverSide: true,
                // URL ini merujuk ke route: /admin/menus/list
                ajax: {
                    "url": "{{ url('admin/menus/list') }}",
                    "dataType": "json",
                    "type": "GET",
                    "data": function(d) {
                        // Mengirimkan nilai filter kategori ke controller
                        d.category_id = $('#category_id').val();
                    }
                },
                columns: [{
                    // Nomor urut dari laravel datatable addIndexColumn()
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "image", // BARU
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "name",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    // Mengambil data kategori hasil dari relasi ORM: menu->category->name
                    data: "category.name",
                    className: "",
                    orderable: false,
                    searchable: false
                }, {
                    // Format harga
                    data: "price",
                    className: "text-right",
                    orderable: true,
                    searchable: false,
                    render: function(data, type, row) {
                        return new Intl.NumberFormat('id-ID').format(data); // Memformat angka
                    }
                }, {
                    // Menampilkan status apakah menu memerlukan level
                    data: "has_level",
                    className: "text-center",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (data == 1) {
                            return '<span class="badge bg-danger">YA</span>';
                        }
                        return '<span class="badge bg-success">TIDAK</span>';
                    }
                }, {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }]
            });

            // Logika filter saat dropdown Kategori diubah
            $('#category_id').change(function() {
                dataMenu.ajax.reload();
            });
        });
    </script>
@endpush
