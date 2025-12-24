@extends('layout.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($order)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data Pesanan yang Anda cari tidak ditemukan.
                </div>
            @else
                <h4>Informasi Pesanan Utama</h4>
                <table class="table table-bordered table-sm mb-4">
                    <tr>
                        <th style="width: 200px;">ID Pesanan</th>
                        <td>{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <th>Nomor Meja</th>
                        <td>{{ $order->table->table_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <td>{{ $order->customer_name }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @php
                                $badgeClass = match($order->status) {
                                    'pending' => 'bg-warning',
                                    'processing' => 'bg-primary',
                                    'completed' => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ strtoupper($order->status) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Waktu Pesan</th>
                        <td>{{ $order->created_at }}</td>
                    </tr>
                </table>

                <h4>Detail Item Pesanan</h4>
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Menu</th>
                            <th>Level</th>
                            <th>Harga Satuan (Rp)</th>
                            <th>Qty</th>
                            <th>Subtotal Item (Rp)</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- PERBAIKAN DISINI: Ganti 'items' jadi 'orderItems' --}}
                        @foreach ($order->orderItems as $item) 
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->menu->name ?? 'Menu Dihapus' }}</td>
                                <td>
                                    {{ $item->level->name ?? '-' }}
                                    @if($item->level && $item->level->extra_cost > 0)
                                        <small>(+Rp {{ number_format($item->level->extra_cost, 0, ',', '.') }})</small>
                                    @endif
                                </td>
                                <td class="text-right">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right">{{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}</td>
                                <td>{{ $item->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-right">Subtotal (Menu + Level Cost)</th>
                            <th class="text-right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="5" class="text-right">PPN (10%)</th>
                            <th class="text-right">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</th>
                            <th></th>
                        </tr>
                        <tr class="bg-dark">
                            <th colspan="5" class="text-right">TOTAL AKHIR</th>
                            <th class="text-right">Rp {{ number_format($order->final_total, 0, ',', '.') }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            @endempty
            <a href="{{ url('admin/orders') }}" class="btn btn-sm btn-default mt-2">Kembali ke Daftar Pesanan</a>
        </div>
    </div>
@endsection