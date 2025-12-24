<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use App\Models\Table;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // --- FUNGSI 1: BUAT PESANAN (STORE) ---
    public function store(Request $request)
    {
        $request->validate([
            'meja'          => 'required', 
            'customer_name' => 'required|string',
            'items'         => 'required|array',
            'items.*.menu_id'  => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.level_id' => 'nullable|exists:levels,id',
            'items.*.notes'    => 'nullable|string',
        ]);

        $nomorMeja = str_pad($request->meja, 2, '0', STR_PAD_LEFT);
        $table = Table::where('table_number', $nomorMeja)->first();

        if (!$table) {
            return response()->json(['success' => false, 'message' => 'Meja '.$nomorMeja.' tidak ditemukan'], 404);
        }

        if ($table->status != 'available') {
            return response()->json(['success' => false, 'message' => 'Meja ini sedang digunakan! Selesaikan pembayaran dulu.'], 400);
        }

        DB::beginTransaction();
        try {
            $runningSubtotal = 0;

            // 1. Buat Order Header
            $order = Order::create([
                'table_id'      => $table->id,
                'customer_name' => $request->customer_name,
                'subtotal'      => 0,
                'tax_amount'    => 0,
                'final_total'   => 0,
                'status'        => 'pending'
            ]);

            // 2. Buat Order Items
            foreach ($request->items as $item) {
                $menu = Menu::find($item['menu_id']);
                $level = isset($item['level_id']) ? Level::find($item['level_id']) : null;

                if ($menu) {
                    $extraCost = $level ? $level->extra_cost : 0;
                    $unitPrice = $menu->price + $extraCost;
                    $lineTotal = $unitPrice * $item['quantity'];
                    $runningSubtotal += $lineTotal;

                    OrderItem::create([
                        'order_id'   => $order->id,
                        'menu_id'    => $menu->id,
                        'level_id'   => $item['level_id'] ?? null,
                        'quantity'   => $item['quantity'],
                        'unit_price' => $unitPrice,
                        'notes'      => $item['notes'] ?? null
                    ]);
                }
            }

            // 3. Update Total & Pajak
            $tax = $runningSubtotal * 0.1;
            $order->update([
                'subtotal'    => $runningSubtotal,
                'tax_amount'  => $tax,
                'final_total' => $runningSubtotal + $tax
            ]);

            // 4. Update Status Meja
            $table->update(['status' => 'occupied']);

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Pesanan berhasil dikirim ke dapur!',
                'data'    => $order->load('orderItems.menu', 'orderItems.level')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // --- FUNGSI 2: BAYAR PESANAN (markAsPaid) ---
    // Pastikan fungsi ini ada di DALAM class OrderController, tapi di LUAR function store()
    public function markAsPaid($id)
    {
        // Pasang debugging biar ketahuan kalau ada error
        try {
            $order = Order::find($id);

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order tidak ditemukan'], 404);
            }

            // --- PERBAIKAN: Ganti 'paid' jadi 'completed' ---
            // (Karena kemungkinan database lo gak kenal status 'paid')
            if ($order->status != 'completed') {
                $order->update(['status' => 'completed']);
            }

            // --- INI YANG PALING PENTING: KOSONGKAN MEJA ---
            // Ubah status meja dari 'occupied' jadi 'available'
            $table = Table::find($order->table_id);
            if ($table) {
                $table->update(['status' => 'available']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran selesai! Meja sudah kosong.',
                'data'    => $order
            ]);

        } catch (\Exception $e) {
            // Kalau masih error, server bakal ngasih tau alasannya
            return response()->json([
                'success' => false, 
                'message' => 'Gagal Update: ' . $e->getMessage()
            ], 500); 
        }
    }

} 