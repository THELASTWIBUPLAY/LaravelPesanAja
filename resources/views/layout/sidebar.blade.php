<div class="sidebar">
    <div class="form-inline mt-2">
        <div class="input-group" data-widget="sidebar-search">
            {{-- PERBAIKAN WARNING: Tambahkan atribut name --}}
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" name="sidebar_search_input">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            
            <li class="nav-header">Manajemen Menu & Meja</li>
            
            {{-- BARU/REVISI: Level Pedasan (Mengambil alih rute /level lama) --}}
            <li class="nav-item">
                <a href="{{ url('admin/levels') }}" class="nav-link {{ $activeMenu == 'level_menu' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-fire-alt"></i>
                    <p>Level Pedasan (Menu)</p>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ url('admin/categories') }}" class="nav-link {{ $activeMenu == 'category' ? 'active' : '' }} ">
                    <i class="nav-icon far fa-bookmark"></i>
                    <p>Kategori Menu</p>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ url('admin/menus') }}" class="nav-link {{ $activeMenu == 'menu' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-utensils"></i>
                    <p>Data Menu</p>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ url('admin/tables') }}" class="nav-link {{ $activeMenu == 'table' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-chair"></i>
                    <p>Data Meja</p>
                </a>
            </li>
            
            <li class="nav-header">Data Transaksi</li>
            
            <li class="nav-item">
                <a href="{{ url('admin/orders') }}" class="nav-link {{ $activeMenu == 'order' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-cash-register"></i>
                    <p>Data Pesanan</p>
                </a>
            </li>
        </ul>
    </nav>
</div>