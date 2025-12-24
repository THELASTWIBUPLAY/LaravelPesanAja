@extends('layout.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('admin/categories/create') }}">Tambah Kategori</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover table-sm" id="table_category">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
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
            var dataCategory = $('#table_category').DataTable({
                serverSide: true, // Untuk menggunakan server-side processing
                ajax: {
                    "url": "{{ url('admin/categories/list') }}", // MUST match the route exactly
                    "dataType": "json",
                    "type": "GET",
                },
                columns: [{
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "name",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "description",
                    className: "",
                    orderable: false,
                    searchable: false
                }, {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }]
            });
        });
    </script>
@endpush
