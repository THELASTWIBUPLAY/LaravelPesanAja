<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Category; 
use Yajra\DataTables\Facades\DataTables; 
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Menampilkan halaman index (Daftar Kategori).
     */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Kategori',
            'list' => ['Home', 'Kategori']
        ];

        $page = (object) [
            'title' => 'Daftar Kategori Restoran'
        ];

        // ... (breadcrumb, page)
        $category = Category::all(); 
        
        // 1. Tentukan activeMenu key yang benar
        $activeMenu = 'category'; // KEY yang harus dipakai untuk Kategori

        // 2. Kirimkan activeMenu
        return view('admin.category.index', compact('breadcrumb', 'page', 'category', 'activeMenu')); // BARU: Tambahkan 'activeMenu'
    }

    /**
     * Mengambil data untuk DataTables (AJAX).
     */
    public function list(Request $request)
    {
        try {
            // Hapus dd() di sini jika masih ada (seperti yang dikomentari)
            // dd($categories->get()); // Pastikan ini dihapus
            
            $categories = Category::select('id', 'name', 'description'); 
            
            return DataTables::of($categories)
                ->addIndexColumn() 
                ->addColumn('aksi', function ($category) { 
                    $btn = '<a href="'.url('/admin/categories/' . $category->id).'" class="btn btn-info btn-sm">Detail</a> ';
                    $btn .= '<a href="'.url('/admin/categories/' . $category->id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                    $btn .= '<form class="d-inline-block" method="POST" action="'.url('/admin/categories/'.$category->id).'">'.
                        csrf_field() . method_field('DELETE') . 
                        '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        } catch (QueryException $e) {
            // Kesalahan terkait database
            // Log the error for debugging
            Log::error('DataTables Query Error: ' . $e->getMessage());
            // Mengembalikan respons JSON yang valid dengan data kosong jika terjadi error
            return response()->json(['data' => [], 'error' => 'Terjadi kesalahan database saat mengambil data.'], 500);
        } catch (Exception $e) {
            // Kesalahan umum lainnya
            // Log the error for debugging
            Log::error('DataTables General Error: ' . $e->getMessage());
            // Mengembalikan respons JSON yang valid dengan data kosong jika terjadi error
            return response()->json(['data' => [], 'error' => 'Terjadi kesalahan umum saat memproses data.'], 500);
        }
    }
    /**
     * Menampilkan halaman tambah Kategori.
     */
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Kategori',
            'list' => ['Home', 'Kategori', 'Tambah']
        ];
        
        $page = (object) [
            'title' => 'Tambah Kategori Baru'
        ];

        // Tentukan activeMenu key yang benar
        $activeMenu = 'category';

        // Kirimkan activeMenu
        return view('admin.category.create', compact('breadcrumb', 'page', 'activeMenu')); // BARU: Tambahkan 'activeMenu'
    }

    /**
     * Menyimpan data Kategori baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:categories,name', // Kategori harus unik
            'description' => 'nullable|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect('/admin/categories')->with('success', 'Data Kategori berhasil disimpan.');
    }

    /**
     * Menampilkan detail Kategori.
     */
    public function show(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
             return redirect('/admin/categories')->with('error', 'Data Kategori tidak ditemukan.');
        }

        $breadcrumb = (object) [
            'title' => 'Manajemen Kategori',
            'list' => ['Home', 'Kategori', 'Detail']
        ];
        
        $page = (object) [
            'title' => 'Detail Kategori'
        ];

        // Tentukan activeMenu key yang benar
        $activeMenu = 'category';

        // Kirimkan activeMenu
        return view('admin.category.show', compact('breadcrumb', 'page', 'category', 'activeMenu')); // BARU: Tambahkan 'activeMenu'
    }

    /**
     * Menampilkan halaman edit Kategori.
     */
    public function edit(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
             return redirect('/admin/categories')->with('error', 'Data Kategori tidak ditemukan.');
        }

        $breadcrumb = (object) [
            'title' => 'Manajemen Kategori',
            'list' => ['Home', 'Kategori', 'Edit']
        ];
        
        $page = (object) [
            'title' => 'Edit Kategori'
        ];

        $activeMenu = 'category';

        // Kirimkan activeMenu
        return view('admin.category.edit', compact('breadcrumb', 'page', 'category', 'activeMenu')); // BARU: Tambahkan 'activeMenu'
    }

    /**
     * Memperbarui data Kategori di database.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:categories,name,'.$id, // Unik, kecuali ID yang sedang diedit
            'description' => 'nullable|string|max:255',
        ]);

        Category::find($id)->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect('/admin/categories')->with('success', 'Data Kategori berhasil diubah.');
    }

    /**
     * Menghapus data Kategori.
     */
    public function destroy(string $id)
    {
        $check = Category::find($id);
        
        if (!$check) {
            return redirect('/admin/categories')->with('error', 'Data Kategori tidak ditemukan.');
        }

        try {
            Category::destroy($id); // Menghapus data
            return redirect('/admin/categories')->with('success', 'Data Kategori berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika ada data Menu yang berelasi dengan Kategori ini
            return redirect('/admin/categories')->with('error', 'Data Kategori gagal dihapus karena masih digunakan dalam data Menu.');
        }
    }
}