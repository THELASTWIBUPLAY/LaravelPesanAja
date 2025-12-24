@extends('layout.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
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
                            <small class="form-text text-muted">Status Pesanan</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <table class="table table-bordered table-striped table-hover table-sm" id="table_order">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nomor Meja</th>
                        <th>Nama Pelanggan</th>
                        <th>Total Akhir (Rp)</th>
                        <th>Status</th>
                        <th>Waktu Pesan</th>
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
            var dataOrder = $('#table_order').DataTable({
                serverSide: true,
                ajax: {
                    // URL ini merujuk ke route: /admin/orders/list
                    "url": "{{ url('admin/orders/list') }}", 
                    "dataType": "json",
                    "type": "GET",
                    "data": function(d) {
                        // Mengirimkan nilai filter status ke controller
                        d.status = $('#status').val();
                    }
                },
                columns: [{
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "table_number", // Diambil dari relasi table->table_number di OrderController::list()
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "customer_name",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "final_total_formatted", // Data yang sudah diformat dari OrderController::list()
                    className: "text-right",
                    orderable: true,
                    searchable: false
                }, {
                    data: "status",
                    className: "text-center",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        var badgeClass = '';
                        if (data === 'pending') badgeClass = 'bg-warning';
                        else if (data === 'processing') badgeClass = 'bg-primary';
                        else if (data === 'completed') badgeClass = 'bg-success';
                        else if (data === 'cancelled') badgeClass = 'bg-danger';
                        return '<span class="badge ' + badgeClass + '">' + data.toUpperCase() + '</span>';
                    }
                }, {
                    data: "created_at",
                    className: "text-center",
                    orderable: true,
                    searchable: false
                }, {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }]
            });

            // Logika filter saat dropdown Status diubah
            $('#status').change(function() {
                dataOrder.ajax.reload();
            });

            // Logika Update Status (Tombol Selesai)
            $('#table_order').on('click', '.update-status', function() {
                var orderId = $(this).data('id');
                var newStatus = $(this).data('status'); // Biasanya 'completed'

                if (confirm('Yakin ingin mengubah status pesanan #' + orderId + ' menjadi ' + newStatus.toUpperCase() + '?')) {
                    $.ajax({
                        url: "{{ url('admin/orders') }}/" + orderId + "/status",
                        type: 'PUT',
                        data: {
                            status: newStatus,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            alert(response.message);
                            dataOrder.ajax.reload();
                        },
                        error: function(xhr) {
                            alert('Gagal mengubah status: ' + xhr.responseJSON.message);
                        }
                    });
                }
            });
        });
    </script>
@endpush