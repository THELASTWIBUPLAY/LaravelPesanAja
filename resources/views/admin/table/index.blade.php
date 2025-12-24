@extends('layout.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('admin/tables/create') }}">Tambah Meja</a>
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
                        <label class="col-1 control-label col-form-label">Filter Status:</label>
                        <div class="col-3">
                            <select class="form-control" id="status" name="status" required>
                                <option value="">Semua Status</option>
                                @foreach ($statuses as $item)
                                    <option value="{{ $item }}">{{ ucfirst($item) }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Status Meja</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <table class="table table-bordered table-striped table-hover table-sm" id="table_table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nomor Meja</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            var dataTable = $('#table_table').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('admin/tables/list') }}", 
                    "dataType": "json",
                    "type": "GET",
                    "data": function(d) {
                        d.status = $('#status').val();
                    }
                },
                columns: [{
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "table_number",
                    className: "text-center",
                    orderable: true,
                    searchable: true
                }, {
                    data: "status",
                    className: "text-center",
                    orderable: true,
                    searchable: false,
                    render: function(data, type, row) {
                        var badgeClass = '';
                        if (data === 'available') badgeClass = 'bg-success';
                        else if (data === 'occupied') badgeClass = 'bg-danger';
                        else if (data === 'cleaning') badgeClass = 'bg-warning';
                        return '<span class="badge ' + badgeClass + '">' + data.toUpperCase() + '</span>';
                    }
                }, {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }]
            });

            $('#status').change(function() {
                dataTable.ajax.reload();
            });
        });
    </script>
@endpush