<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Menu;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    // --- FUNGSI ADMIN VIEW ---

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Pesanan',
            'list' => ['Home', 'Pesanan']
        ];

        $page = (object) [
            'title' => 'Daftar Pesanan yang Sedang Diproses'
        ];

        // Status filter: pending, processing, completed, cancelled
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        
        // 👇 SOLUSI: Tambahkan $activeMenu
        $activeMenu = 'order';

        return view('admin.order.index', compact('breadcrumb', 'page', 'statuses', 'activeMenu'));
    }

    public function list(Request $request)
    {
        // Eager load relasi table
        $orders = Order::select('id', 'table_id', 'customer_name', 'final_total', 'status', 'created_at')
                        ->with('table')
                        ->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($request->status) {
            $orders->where('status', $request->status);
        }

        return DataTables::of($orders)
            ->addIndexColumn() 
            ->addColumn('table_number', function ($order) {
                return $order->table->table_number ?? 'N/A';
            })
            ->addColumn('final_total_formatted', function ($order) {
                return 'Rp ' . number_format($order->final_total, 0, ',', '.');
            })
            ->addColumn('aksi', function ($order) {
                // Tombol detail, dan tombol update status
                $btn = '<a href="'.url('/admin/orders/' . $order->id).'" class="btn btn-info btn-sm">Detail</a> ';
                // Contoh: Tombol Selesaikan Pesanan (hanya jika status processing)
                if ($order->status == 'processing') {
                    $btn .= '<button class="btn btn-success btn-sm ml-1 update-status" data-id="'.$order->id.'" data-status="completed">Selesai</button>';
                }
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show(string $id)
    {
        // Eager load relasi table dan items (dengan menu dan level)
        $order = Order::with('table', 'orderItems.menu', 'orderItems.level')->find($id);

        if (!$order) {
             return redirect('/admin/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        $breadcrumb = (object) [
            'title' => 'Detail Pesanan',
            'list' => ['Home', 'Pesanan', 'Detail']
        ];
        
        $page = (object) [
            'title' => 'Detail Pesanan #' . $order->id
        ];
        
        // 👇 SOLUSI: Tambahkan $activeMenu
        $activeMenu = 'order';

        // Anda perlu membuat view: admin/order/show.blade.php
        return view('admin.order.show', compact('breadcrumb', 'page', 'order', 'activeMenu'));
    }

    // Fungsi untuk memperbarui status pesanan (dipanggil dari AJAX admin)
    public function updateStatus(Request $request, string $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        $newStatus = $request->status;

        // Validasi status yang diizinkan
        if (!in_array($newStatus, ['pending', 'processing', 'completed', 'cancelled'])) {
            return response()->json(['success' => false, 'message' => 'Status tidak valid.'], 400);
        }
        
        // Mulai transaksi database
        DB::beginTransaction();
        try {
            $order->status = $newStatus;
            $order->save();

            // Jika pesanan selesai, kembalikan status meja menjadi available
            if ($newStatus == 'completed' || $newStatus == 'cancelled') {
                $order->table->status = 'available';
                $order->table->save();
            } else if ($newStatus == 'pending' && $order->table) {
                // Pastikan meja menjadi occupied saat order masuk atau diproses
                $order->table->status = 'occupied';
                $order->table->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Status pesanan berhasil diperbarui.'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status: '.$e->getMessage()], 500);
        }
    }


    // --- FUNGSI API (UNTUK CHECKOUT DARI ANDROID) ---

    public function store(Request $request)
    {
        // 1. Validasi Input dari Android
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_name' => 'required|string|max:100',
            'items' => 'required|array',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.level_id' => 'nullable|exists:levels,id', // Opsional jika menu tidak berlevel
            'items.*.notes' => 'nullable|string|max:255',
        ]);

        $itemsData = $request->items;
        $subtotal = 0;

        // 2. Cek status meja sebelum memulai transaksi
        $table = Table::find($request->table_id);
        if ($table->status != 'available') {
            return response()->json(['message' => 'Meja sedang digunakan. Pilih meja lain.'], 400);
        }

        // Mulai Transaksi Database
        DB::beginTransaction();

        try {
            // 3. Hitung Total dan Buat Order Header
            foreach ($itemsData as $item) {
                $menu = Menu::find($item['menu_id']); // Menu Harga Dasar
                $level = $item['level_id'] ? Level::find($item['level_id']) : null; // Level (jika ada)

                $basePrice = $menu->price;
                $levelCost = $level ? $level->extra_cost : 0;
                $unitPrice = $basePrice + $levelCost; // Harga Satuan per Item (Menu + Biaya Level)
                
                $subtotal += ($unitPrice * $item['quantity']);
            }

            $taxRate = 0.10; // 10% PPN
            $taxAmount = $subtotal * $taxRate;
            $finalTotal = $subtotal + $taxAmount;

            // Buat Pesanan Baru (Order Header)
            $order = Order::create([
                'table_id' => $request->table_id,
                'customer_name' => $request->customer_name,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'final_total' => $finalTotal,
                'status' => 'pending', // Status awal saat pesanan masuk
            ]);

            // 4. Buat Order Item (Detail)
            foreach ($itemsData as $item) {
                $menu = Menu::find($item['menu_id']); 
                $level = $item['level_id'] ? Level::find($item['level_id']) : null;
                $levelCost = $level ? $level->extra_cost : 0;
                $unitPrice = $menu->price + $levelCost;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'level_id' => $item['level_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice, // Simpan harga saat dipesan
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            // 5. Update Status Meja
            $table->status = 'occupied';
            $table->save();

            DB::commit();

            return response()->json([
                'message' => 'Pesanan berhasil dibuat. Mohon tunggu.',
                'order_id' => $order->id,
                'final_total' => $finalTotal
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal membuat pesanan: ' . $e->getMessage()], 500);
        }
    }
}