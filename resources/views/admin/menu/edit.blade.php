@extends('layout.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($menu)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan! Data Menu tidak ditemukan.</h5>
                </div>
                <a href="{{ url('admin/menus') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
                {{-- PERUBAHAN KRUSIAL: Tambahkan enctype="multipart/form-data" --}}
                <form method="POST" action="{{ url('/admin/menus/' . $menu->id) }}" class="form-horizontal"
                    enctype="multipart/form-data">
                    @csrf
                    {!! method_field('PUT') !!} {{-- Method spoofing untuk update --}}

                    {{-- 1. PILIH KATEGORI --}}
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Kategori</label>
                        <div class="col-11">
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="">- Pilih Kategori -</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}" @if (old('category_id', $menu->category_id) == $item->id) selected @endif>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- 2. NAMA MENU --}}
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Nama Menu</label>
                        <div class="col-11">
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $menu->name) }}" required>
                            @error('name')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- 3. DESKRIPSI --}}
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Deskripsi</label>
                        <div class="col-11">
                            <textarea class="form-control" id="description" name="description">{{ old('description', $menu->description) }}</textarea>
                            @error('description')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- 4. FOTO SAAT INI & GANTI FOTO (BARU DITAMBAHKAN) --}}
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Foto Menu</label>
                        <div class="col-11">
                            {{-- Tampilkan foto yang sudah ada --}}
                            @if ($menu->image)
                                {{-- Gunakan asset('storage/...') --}}
                                {{-- Contoh di edit.blade.php --}}
                                <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" style="max-width: 150px;">
                                <div class="form-check">
                                    {{-- Checkbox untuk menghapus gambar, nilainya akan dikirim ke controller --}}
                                    <input class="form-check-input" type="checkbox" value="1" id="remove_image"
                                        name="remove_image">
                                    <label class="form-check-label" for="remove_image">Hapus Foto Saat Ini</label>
                                </div>
                            @else
                                <p>Belum ada foto.</p>
                            @endif

                            {{-- Input untuk mengunggah gambar baru --}}
                            <input type="file" class="form-control mt-2" id="image" name="image">
                            @error('image')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                            <small class="form-text text-muted">Abaikan jika tidak ingin mengganti. Max 2MB (JPG/PNG)</small>
                        </div>
                    </div>

                    {{-- 5. HARGA --}}
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Harga (Rp)</label>
                        <div class="col-11">
                            <input type="number" class="form-control" id="price" name="price"
                                value="{{ old('price', $menu->price) }}" required min="0">
                            @error('price')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- 6. PERLU LEVEL? (YA/TIDAK) --}}
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Perlu Level?</label>
                        <div class="col-11">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="has_level" id="level_ya" value="1"
                                    {{ old('has_level', $menu->has_level) == 1 ? 'checked' : '' }} required>
                                <label class="form-check-label" for="level_ya">Ya (Contoh: Mie Pedas)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="has_level" id="level_tidak" value="0"
                                    {{ old('has_level', $menu->has_level) == 0 ? 'checked' : '' }} required>
                                <label class="form-check-label" for="level_tidak">Tidak (Contoh: Minuman)</label>
                            </div>
                            @error('has_level')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- TOMBOL AKSI --}}
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label"></label>
                        <div class="col-11">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            <a class="btn btn-sm btn-default ml-1" href="{{ url('admin/menus') }}">Kembali</a>
                        </div>
                    </div>
                </form>
            @endempty
        </div>
    </div>
@endsection
