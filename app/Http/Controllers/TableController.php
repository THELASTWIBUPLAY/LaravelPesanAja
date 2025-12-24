<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TableController extends Controller
{
    /**
     * Menampilkan halaman index (Daftar Meja).
     */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Meja',
            'list' => ['Home', 'Meja']
        ];

        $page = (object) [
            'title' => 'Daftar Meja Restoran'
        ];

        $statuses = ['available', 'occupied', 'cleaning']; 

        $activeMenu = 'table'; // Tambahkan ini

        return view('admin.table.index', compact('breadcrumb', 'page', 'statuses', 'activeMenu'));
    }

    /**
     * Mengambil data untuk DataTables (AJAX).
     */
    public function list(Request $request)
    {
        $tables = Table::select('id', 'table_number', 'status'); 
        
        // Filter berdasarkan status
        if ($request->status) {
            $tables->where('status', $request->status);
        }
        
        return DataTables::of($tables)
            ->addIndexColumn() 
            ->addColumn('aksi', function ($table) {
                $btn = '<a href="'.url('/admin/tables/' . $table->id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('/admin/tables/' . $table->id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'.url('/admin/tables/'.$table->id).'">'.
                    csrf_field() . method_field('DELETE') . 
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Menampilkan halaman tambah Meja.
     */
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Meja',
            'list' => ['Home', 'Meja', 'Tambah']
        ];
        
        $page = (object) [
            'title' => 'Tambah Meja Baru'
        ];

        $activeMenu = 'table'; // Tambahkan ini

        return view('admin.table.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    /**
     * Menyimpan data Meja baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|max:10|unique:tables,table_number',
            'status' => 'required|in:available,occupied,cleaning',
        ]);

        Table::create([
            'table_number' => $request->table_number,
            'status' => $request->status,
        ]);

        return redirect('/admin/tables')->with('success', 'Data Meja berhasil disimpan.');
    }

    /**
     * Menampilkan detail Meja.
     */
    public function show(string $id)
    {
        $table = Table::find($id);

        if (!$table) {
             return redirect('/admin/tables')->with('error', 'Data Meja tidak ditemukan.');
        }

        $breadcrumb = (object) [
            'title' => 'Manajemen Meja',
            'list' => ['Home', 'Meja', 'Detail']
        ];
        
        $page = (object) [
            'title' => 'Detail Meja'
        ];

        $activeMenu = 'table'; // Tambahkan ini

        return view('admin.table.show', compact('breadcrumb', 'page', 'table', 'activeMenu'));
    }

    /**
     * Menampilkan halaman edit Meja.
     */
    public function edit(string $id)
    {
        $table = Table::find($id);

        if (!$table) {
             return redirect('/admin/tables')->with('error', 'Data Meja tidak ditemukan.');
        }

        $breadcrumb = (object) [
            'title' => 'Manajemen Meja',
            'list' => ['Home', 'Meja', 'Edit']
        ];
        
        $page = (object) [
            'title' => 'Edit Meja'
        ];

        $statuses = ['available', 'occupied', 'cleaning']; 

        $activeMenu = 'table'; // Tambahkan ini

        return view('admin.table.edit', compact('breadcrumb', 'page', 'table', 'statuses', 'activeMenu'));
    }

    /**
     * Memperbarui data Meja di database.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'table_number' => 'required|string|max:10|unique:tables,table_number,'.$id,
            'status' => 'required|in:available,occupied,cleaning',
        ]);

        Table::find($id)->update([
            'table_number' => $request->table_number,
            'status' => $request->status,
        ]);

        return redirect('/admin/tables')->with('success', 'Data Meja berhasil diubah.');
    }

    /**
     * Menghapus data Meja.
     */
    public function destroy(string $id)
    {
        $check = Table::find($id);
        
        if (!$check) {
            return redirect('/admin/tables')->with('error', 'Data Meja tidak ditemukan.');
        }

        try {
            Table::destroy($id);
            return redirect('/admin/tables')->with('success', 'Data Meja berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Karena Meja berelasi dengan Order, jika ada pesanan yang terhubung, hapus akan gagal
            return redirect('/admin/tables')->with('error', 'Data Meja gagal dihapus karena masih terhubung dengan Pesanan yang ada.');
        }
    }
}