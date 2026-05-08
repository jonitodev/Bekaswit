{{-- @author Gilang Bayu Irwana - 244107020194 --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bekaswit - Bekas Jadi Duit')</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-bekaswit sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-recycle"></i> Bekaswit
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto ms-lg-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barang.search') ? 'active' : '' }}" href="{{ route('barang.search') }}">
                            Cari Barang
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto gap-1">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Masuk Penjual</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-nav-daftar ms-1" href="{{ route('register') }}">Daftar Penjual</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white" style="width:30px; height:30px; font-size:13px; font-weight:700;">
                                    {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                                </span>
                                {{ Auth::user()->nama }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(Auth::user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item fw-semibold" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i>Admin Dashboard
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}" href="{{ route('seller.dashboard') }}">
                                        <i class="bi bi-graph-up me-2"></i>Dashboard Penjual
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profil.edit') }}">
                                        <i class="bi bi-person me-2"></i>Profil Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('listing.index') }}">
                                        <i class="bi bi-box-seam me-2"></i>Listing Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('barang.create') }}">
                                        <i class="bi bi-plus-circle me-2"></i>Jual Barang
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-bekaswit text-center mt-auto">
        <div class="container">
            &copy; 2026 Bekaswit &mdash; Bekas Jadi Duit &middot; Politeknik Negeri Malang
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
