{{-- @author Silva Tria Alfares - 254107023001 --}}
{{-- // test from alfa --}}


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Bekaswit</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <i class="bi bi-recycle"></i> Bekaswit
            <span class="badge bg-light text-dark">Admin</span>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Penjual
            </a>
            <a href="{{ route('admin.barang.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.barang.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Barang
            </a>
            <a href="{{ route('admin.kategori.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Kategori
            </a>
            <a href="{{ route('admin.area.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.area.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt"></i> Area
            </a>

            <div class="sidebar-divider"></div>

            <a href="{{ route('home') }}" class="sidebar-link">
                <i class="bi bi-arrow-left"></i> Kembali ke Situs
            </a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="sidebar-link w-100 text-start border-0 bg-transparent">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </nav>
    </aside>

    <!-- Mobile Toggle -->
    <button class="btn d-lg-none admin-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <!-- Overlay -->
    <div class="sidebar-overlay d-lg-none" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <div class="admin-content">
        <div class="admin-topbar">
            <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
            <div class="d-flex align-items-center gap-2">
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white"
                    style="width:32px; height:32px; font-size:13px; font-weight:700; background:#3563E9;">
                    {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                </span>
                <div class="d-flex flex-column lh-sm">
                    <span class="fw-semibold small">{{ Auth::user()->nama }}</span>
                    <span class="text-muted" style="font-size:11px;">Administrator</span>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show flash-alert" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show flash-alert" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /* @author Silva Tria Alfares - 254107023001 */
        const toggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (toggle && sidebar && overlay) {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }

        document.querySelectorAll('.flash-alert').forEach(el => {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
                bsAlert.close();
            }, 5000);
        });

        function confirmDelete(formId, message) {
            if (confirm(message || 'Apakah Anda yakin ingin menghapus?')) {
                document.getElementById(formId).submit();
            }
        }
    </script>

    @stack('scripts')
</body>

</html>
