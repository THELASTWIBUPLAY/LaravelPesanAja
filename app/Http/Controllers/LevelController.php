<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    /**
     * Menampilkan halaman index (Daftar Level).
     */
    public function index()
    {
        $breadcrumb = (object) [
            // Ganti Judul
            'title' => 'Manajemen Level Pedasan',
            'list' => ['Home', 'Level Pedasan']
        ];

        $page = (object) [
            'title' => 'Daftar Level Pedasan'
        ];

        // 燥 PERUBAHAN KRUSIAL: Ganti 'level' menjadi 'level_menu'
        $activeMenu = 'level_menu';

        // Kirimkan $activeMenu
        return view('admin.level.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    /**
     * Ambil data Level dalam format json untuk datatables.
     */
    public function list(Request $request)
    {
        $levels = Level::select('id', 'name', 'code', 'extra_cost');

        // Jika ada filter, tambahkan di sini (saat ini tidak ada)

        return DataTables::of($levels)
            ->addIndexColumn() // Menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($level) { // Menambahkan kolom aksi
                $btn = '<a href="' . url('/admin/levels/' . $level->id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/admin/levels/' . $level->id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';

                // 🎯 FIX: Mengganti @csrf dan @method("DELETE") dengan input HTML yang setara
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/admin/levels/' . $level->id) . '">';

                // Tambahkan token CSRF secara manual:
                $btn .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';

                // Tambahkan spoofing method DELETE secara manual:
                $btn .= '<input type="hidden" name="_method" value="DELETE">';

                $btn .= '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Menampilkan halaman tambah Level.
     */
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Level Pedasan',
            'list' => ['Home', 'Level Pedasan', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Level Pedasan Baru'
        ];

        // 燥 PERUBAHAN KRUSIAL: Ganti 'level' menjadi 'level_menu'
        $activeMenu = 'level_menu';

        // Kirimkan $activeMenu
        return view('admin.level.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    /**
     * Menyimpan data Level baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            // name harus diisi, berupa string, maksimal 50 karakter, dan unik di tabel levels
            'name' => 'required|string|max:50|unique:levels,name',
            // code boleh kosong, berupa string, maksimal 10 karakter, dan unik di tabel levels
            'code' => 'nullable|string|max:10|unique:levels,code',
            // description boleh kosong, berupa string, maksimal 255 karakter
            'description' => 'nullable|string|max:255',
            // extra_cost wajib diisi, berupa angka (numeric), minimal 0
            'extra_cost' => 'required|numeric|min:0',
        ]);

        Level::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'extra_cost' => $request->extra_cost,
        ]);

        // Setelah berhasil menyimpan, redirect ke halaman index Level
        return redirect('/admin/levels')->with('success', 'Data Level berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail Level.
     */
    public function show(string $id)
    {
        $level = Level::find($id);

        if (!$level) {
            // 🎯 FIX: Ganti /level menjadi /admin/levels
            return redirect('/admin/levels')->with('error', 'Data Level tidak ditemukan.');
        }

        $breadcrumb = (object) [
            'title' => 'Manajemen Level Pedasan',
            'list' => ['Home', 'Level Pedasan', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Level Pedasan'
        ];

        // 燥 PERUBAHAN KRUSIAL: Ganti 'level' menjadi 'level_menu'
        $activeMenu = 'level_menu';

        // Kirimkan $activeMenu
        return view('admin.level.show', compact('breadcrumb', 'page', 'level', 'activeMenu'));
    }

    /**
     * Menampilkan halaman edit Level.
     */
    public function edit(string $id)
    {
        $level = Level::find($id);

        if (!$level) {
            // 🎯 FIX: Ganti /level menjadi /admin/levels
            return redirect('/admin/levels')->with('error', 'Data Level tidak ditemukan.');
        }

        $breadcrumb = (object) [
            'title' => 'Manajemen Level Pedasan',
            'list' => ['Home', 'Level Pedasan', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Level Pedasan'
        ];

        // 燥 PERUBAHAN KRUSIAL: Ganti 'level' menjadi 'level_menu'
        $activeMenu = 'level_menu';

        // Kirimkan $activeMenu
        return view('admin.level.edit', compact('breadcrumb', 'page', 'level', 'activeMenu'));
    }

    /**
     * Memperbarui data Level di database.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            // name harus diisi, string, max 50, dan unik di tabel levels kecuali untuk id ini
            'name' => 'required|string|max:50|unique:levels,name,' . $id,
            // code boleh kosong, string, max 10, dan unik di tabel levels kecuali untuk id ini
            'code' => 'nullable|string|max:10|unique:levels,code,' . $id,
            'description' => 'nullable|string|max:255',
            'extra_cost' => 'required|numeric|min:0', // Validasi untuk biaya tambahan
        ]);

        Level::find($id)->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'extra_cost' => $request->extra_cost,
        ]);

        // 🎯 FIX: Ganti /level menjadi /admin/levels
        return redirect('/admin/levels')->with('success', 'Data Level berhasil diubah.');
    }

    /**
     * Menghapus data Level.
     */
    public function destroy(string $id)
    {
        $check = Level::find($id);

        if (!$check) {
            // 🎯 FIX: Ganti /level menjadi /admin/levels
            return redirect('/admin/levels')->with('error', 'Data Level tidak ditemukan.');
        }

        try {
            Level::destroy($id);
            // 🎯 FIX: Ganti /level menjadi /admin/levels
            return redirect('/admin/levels')->with('success', 'Data Level berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Asumsi Level berelasi dengan User ATAU OrderItem
            // 🎯 FIX: Ganti /level menjadi /admin/levels
            return redirect('/admin/levels')->with('error', 'Data Level gagal dihapus karena masih digunakan oleh User atau Pesanan.');
        }
    }
}
