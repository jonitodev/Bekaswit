{{-- @author Silva Tria Alfares - 254107023001 --}}
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#161514">
    <title>@yield('title', 'Dasbor Admin') — Bekaswit</title>

    {{-- Pre-apply theme to avoid FOUC --}}
    <script>
        (function () {
            try {
                var t = localStorage.getItem('bekaswit-theme');
                if (!t) t = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                document.documentElement.setAttribute('data-theme', t);
            } catch (e) {}
        })();
    </script>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

    {{-- Sidebar --}}
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <span class="brand-mark">B</span>
            <span>bekaswit</span>
            <span class="badge">Admin</span>
        </div>

        <div class="sidebar-section-label">Ringkasan</div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dasbor
            </a>

            <div class="sidebar-section-label">Manajemen</div>

            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Penjual
            </a>
            <a href="{{ route('admin.barang.index') }}" class="sidebar-link {{ request()->routeIs('admin.barang.*') && request('approval') !== 'pending' ? 'active' : '' }}">
                <i class="bi bi-bag"></i> Barang
            </a>
            <a href="{{ route('admin.barang.index', ['approval' => 'pending']) }}" class="sidebar-link d-flex align-items-center {{ request()->routeIs('admin.barang.index') && request('approval') === 'pending' ? 'active' : '' }}">
                <i class="bi bi-patch-check"></i> Persetujuan
                @if(($pendingApprovalCount ?? 0) > 0)
                    <span class="badge bg-warning text-dark ms-auto">{{ $pendingApprovalCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.banner.index') }}" class="sidebar-link {{ request()->routeIs('admin.banner.*') ? 'active' : '' }}">
                <i class="bi bi-images"></i> Banner
            </a>
            <a href="{{ route('admin.kategori.index') }}" class="sidebar-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Kategori
            </a>
            <a href="{{ route('admin.area.index') }}" class="sidebar-link {{ request()->routeIs('admin.area.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt"></i> Area
            </a>

            <div class="sidebar-divider"></div>

            <a href="{{ route('home') }}" class="sidebar-link">
                <i class="bi bi-arrow-left-circle"></i> Kembali ke Situs
            </a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="sidebar-link w-100 text-start border-0 bg-transparent">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </nav>
    </aside>

    {{-- Mobile Toggle --}}
    <button class="btn d-lg-none admin-toggle" id="sidebarToggle" aria-label="Buka atau tutup bilah sisi">
        <i class="bi bi-list"></i>
    </button>

    {{-- Overlay --}}
    <div class="sidebar-overlay d-lg-none" id="sidebarOverlay"></div>

    {{-- Main Content --}}
    <div class="admin-content">
        <div class="admin-topbar">
            <h5>@yield('page-title', 'Dasbor')</h5>
            <div class="topbar-meta">
                {{-- Theme toggle --}}
                <button type="button" class="topbar-icon" id="themeToggle" aria-label="Ubah mode gelap" title="Ubah tema">
                    <i class="bi bi-moon-stars" data-icon="moon"></i>
                    <i class="bi bi-sun d-none" data-icon="sun"></i>
                </button>

                {{-- Notifications (placeholder) --}}
                <button type="button" class="topbar-icon" aria-label="Notifikasi">
                    <i class="bi bi-bell"></i>
                </button>

                <div class="topbar-user">
                    <span class="avatar">{{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}</span>
                    <div class="meta">
                        <span class="name">{{ Auth::user()->nama }}</span>
                        <span class="role">Administrator</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show flash-alert" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
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
        (function () {
            // Sidebar mobile toggle
            var toggle  = document.getElementById('sidebarToggle');
            var sidebar = document.getElementById('adminSidebar');
            var overlay = document.getElementById('sidebarOverlay');
            if (toggle && sidebar && overlay) {
                toggle.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });
                overlay.addEventListener('click', function () {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }

            // Auto-close flash alerts after 5s
            document.querySelectorAll('.flash-alert').forEach(function (el) {
                setTimeout(function () {
                    var bsAlert = bootstrap.Alert.getOrCreateInstance(el);
                    bsAlert.close();
                }, 5000);
            });

            // Theme toggle (synced with public site via same localStorage key)
            var html = document.documentElement;
            var btn  = document.getElementById('themeToggle');
            function syncIcons() {
                if (!btn) return;
                var isDark = html.getAttribute('data-theme') === 'dark';
                var moon = btn.querySelector('[data-icon="moon"]');
                var sun  = btn.querySelector('[data-icon="sun"]');
                if (moon) moon.classList.toggle('d-none', isDark);
                if (sun)  sun.classList.toggle('d-none', !isDark);
            }
            syncIcons();
            if (btn) {
                btn.addEventListener('click', function () {
                    var next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                    html.setAttribute('data-theme', next);
                    try { localStorage.setItem('bekaswit-theme', next); } catch (e) {}
                    syncIcons();
                });
            }
        })();

        // Reusable confirm-delete helper
        function confirmDelete(formId, message) {
            if (confirm(message || 'Apakah Anda yakin ingin menghapus?')) {
                document.getElementById(formId).submit();
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
