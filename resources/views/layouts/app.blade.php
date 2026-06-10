{{-- @author Gilang Bayu Irwana - 244107020194 --}}
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#FBF8F3">
    <title>@yield('title', 'Bekaswit — Temukan Barang Bekas Pilihan Anda')</title>

    {{-- Pre-apply theme to avoid FOUC --}}
    <script>
        (function () {
            try {
                var t = localStorage.getItem('bekaswit-theme');
                if (!t) {
                    t = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }
                document.documentElement.setAttribute('data-theme', t);
            } catch (e) {}
        })();
    </script>

    {{-- Fonts: Playfair Display (display) + DM Sans (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 (grid + dropdown components only) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- App CSS --}}
    <link href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}" rel="stylesheet">

    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-bekaswit sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <span class="brand-mark">B</span>
                <span>bekaswit</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-label="Buka atau tutup navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                {{-- Search bar (centered, expanding) --}}
                <form method="GET" action="{{ route('barang.search') }}" class="nav-search ms-lg-4 my-2 my-lg-0 flex-grow-1" style="max-width: 460px;">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" placeholder="Cari busana, buku, elektronik..." value="{{ request('q') }}" autocomplete="off">
                </form>

                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2 mt-3 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Belanja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barang.search') ? 'active' : '' }}" href="{{ route('barang.search') }}">Jelajahi</a>
                    </li>

                    {{-- Theme toggle --}}
                    <li class="nav-item d-flex align-items-center">
                        <button type="button" class="icon-action theme-toggle" id="themeToggle" aria-label="Ubah mode gelap" title="Ubah tema">
                            <i class="bi bi-moon-stars" data-icon="moon"></i>
                            <i class="bi bi-sun d-none" data-icon="sun"></i>
                        </button>
                    </li>

                    {{-- Cart with animated badge --}}
                    <li class="nav-item d-flex align-items-center">
                        <button type="button" class="icon-action" aria-label="Keranjang" id="navCart"
                                data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                            <i class="bi bi-bag"></i>
                            <span class="cart-badge d-none" id="cartBadge">0</span>
                        </button>
                    </li>

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-nav-daftar ms-1" href="{{ route('register') }}">Daftar</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white"
                                      style="width:32px; height:32px; font-size:13px; font-weight:700; background:var(--accent);">
                                    {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                                </span>
                                <span class="d-none d-md-inline">{{ Str::limit(Auth::user()->nama, 14) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(Auth::user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item fw-semibold" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i>Dasbor Admin
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}" href="{{ route('seller.dashboard') }}">
                                        <i class="bi bi-graph-up me-2"></i>Dasbor Penjual
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profil.edit') }}">
                                        <i class="bi bi-person me-2"></i>Profil Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('listing.index') }}">
                                        <i class="bi bi-box-seam me-2"></i>Daftar Barang Saya
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

    {{-- Flash Messages --}}
    @if(session('success') || session('error'))
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
    @endif

    {{-- Main Content --}}
    <main class="flex-grow-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="footer-bekaswit mt-auto">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a class="navbar-brand p-0" href="{{ route('home') }}">
                        <span class="brand-mark">B</span>
                        <span>bekaswit</span>
                    </a>
                    <p class="brand-blurb">
                        Lokapasar barang bekas untuk anak indekos di Malang. Temukan cerita baru dari barang lama — hemat, berkelanjutan, dan estetik.
                    </p>
                </div>
                <div>
                    <h6>Belanja</h6>
                    <ul>
                        <li><a href="{{ route('barang.search') }}">Semua Barang</a></li>
                        <li><a href="{{ route('barang.search') }}?sort=terbaru">Baru Masuk</a></li>
                        <li><a href="{{ route('barang.search') }}?sort=harga_asc">Harga Termurah</a></li>
                        <li><a href="{{ route('barang.search') }}">Barang Langka</a></li>
                    </ul>
                </div>
                <div>
                    <h6>Bantuan</h6>
                    <ul>
                        <li><a href="#">Cara Berbelanja</a></li>
                        <li><a href="#">Cara Menjual</a></li>
                        <li><a href="#">Tanya Jawab</a></li>
                        <li><a href="#">Hubungi Kami</a></li>
                    </ul>
                </div>
                <div>
                    <h6>Tentang</h6>
                    <ul>
                        <li><a href="#">Cerita Kami</a></li>
                        <li><a href="#">Keberlanjutan</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Syarat &amp; Ketentuan</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                &copy; {{ date('Y') }} Bekaswit &mdash; Bekas Jadi Duit · Politeknik Negeri Malang
            </div>
        </div>
    </footer>

    {{-- Mobile Bottom Navigation --}}
    <nav class="bottom-nav" aria-label="Navigasi seluler">
        <div class="container-fluid">
            <div class="bottom-nav-inner">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="bi bi-house"></i><span>Beranda</span>
                </a>
                <a href="{{ route('barang.search') }}" class="{{ request()->routeIs('barang.search') ? 'active' : '' }}">
                    <i class="bi bi-compass"></i><span>Jelajahi</span>
                </a>
                <a href="{{ Auth::check() ? route('barang.create') : route('login') }}" class="center" aria-label="Jual Barang">
                    <i class="bi bi-plus-lg"></i><span>Jual</span>
                </a>
                <a href="#"><i class="bi bi-chat-dots"></i><span>Obrolan</span></a>
                <a href="{{ Auth::check() ? route('profil.edit') : route('login') }}">
                    <i class="bi bi-person"></i><span>Profil</span>
                </a>
            </div>
        </div>
    </nav>

    {{-- Floating Sell button (desktop) --}}
    @auth
        <a href="{{ route('barang.create') }}" class="fab-jual d-none d-md-inline-flex" aria-label="Jual Barang">
            <i class="bi bi-plus-circle"></i> Jual Barang
        </a>
    @else
        <a href="{{ route('login') }}" class="fab-jual d-none d-md-inline-flex" aria-label="Jual Barang">
            <i class="bi bi-plus-circle"></i> Jual Barang
        </a>
    @endauth

    {{-- Cart Drawer (offcanvas) --}}
    <div class="offcanvas offcanvas-end cart-drawer" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartTitle">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="cartTitle">
                <i class="bi bi-bag-heart"></i> Keranjang Saya
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
        </div>
        <div class="offcanvas-body">
            <div class="cart-empty" id="cartEmpty">
                <i class="bi bi-bag"></i>
                <p>Keranjang Anda masih kosong.</p>
                <a href="{{ route('barang.search') }}" class="btn btn-outline-primary btn-sm" data-bs-dismiss="offcanvas">
                    Jelajahi Barang
                </a>
            </div>
            <div class="cart-list" id="cartItems"></div>
        </div>
        <div class="cart-foot" id="cartFoot" hidden>
            <div class="cart-subtotal">
                <span>Total perkiraan</span>
                <strong id="cartSubtotal">Rp 0</strong>
            </div>
            <p class="cart-note">Transaksi dilakukan langsung dengan penjual via WhatsApp.</p>
            <div id="cartCheckout"></div>
            <button type="button" class="btn btn-link btn-sm text-muted w-100 mt-1" id="cartClear">
                Kosongkan keranjang
            </button>
        </div>
    </div>

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Cart system (localStorage + WhatsApp checkout) --}}
    <script>
        (function () {
            var KEY = 'bekaswit-cart';
            var rupiah = function (n) { return 'Rp ' + (n || 0).toLocaleString('id-ID'); };

            function load() {
                try { return JSON.parse(localStorage.getItem(KEY) || '[]'); }
                catch (e) { return []; }
            }
            function save(arr) {
                try { localStorage.setItem(KEY, JSON.stringify(arr)); } catch (e) {}
            }

            var cart = load();

            function normalizeWa(wa) {
                if (!wa) return '';
                var n = ('' + wa).replace(/[^0-9]/g, '');
                if (n.indexOf('0') === 0) n = '62' + n.slice(1);
                else if (n.indexOf('62') !== 0 && n.indexOf('8') === 0) n = '62' + n;
                return n;
            }

            function badge() {
                var el = document.getElementById('cartBadge');
                if (!el) return;
                el.textContent = cart.length;
                el.classList.toggle('d-none', cart.length === 0);
                el.style.animation = 'none';
                void el.offsetWidth;
                el.style.animation = '';
            }

            function buildCheckout() {
                var wrap = document.getElementById('cartCheckout');
                if (!wrap) return;
                wrap.innerHTML = '';
                // Group items by seller (wa)
                var groups = {};
                cart.forEach(function (it) {
                    var key = it.wa || '__none__';
                    if (!groups[key]) groups[key] = { penjual: it.penjual || 'Penjual', items: [] };
                    groups[key].items.push(it);
                });
                Object.keys(groups).forEach(function (key) {
                    var g = groups[key];
                    if (key === '__none__') {
                        var note = document.createElement('p');
                        note.className = 'cart-note text-warning';
                        note.innerHTML = '<i class="bi bi-exclamation-circle"></i> ' + g.items.length + ' barang tanpa kontak penjual.';
                        wrap.appendChild(note);
                        return;
                    }
                    var lines = g.items.map(function (it) {
                        return '- ' + it.nama + ' (' + rupiah(it.harga) + ')';
                    }).join('\n');
                    var msg = 'Halo ' + g.penjual + ', saya tertarik dengan barang berikut di Bekaswit:\n' + lines + '\n\nApakah masih tersedia?';
                    var a = document.createElement('a');
                    a.href = 'https://wa.me/' + normalizeWa(key) + '?text=' + encodeURIComponent(msg);
                    a.target = '_blank';
                    a.rel = 'noopener';
                    a.className = 'btn btn-whatsapp w-100 mb-2';
                    a.innerHTML = '<i class="bi bi-whatsapp"></i> Pesan ke ' + g.penjual + ' (' + g.items.length + ')';
                    wrap.appendChild(a);
                });
            }

            function render() {
                var list  = document.getElementById('cartItems');
                var empty = document.getElementById('cartEmpty');
                var foot  = document.getElementById('cartFoot');
                if (!list) return;

                if (!cart.length) {
                    list.innerHTML = '';
                    if (empty) empty.style.display = '';
                    if (foot) foot.hidden = true;
                    badge();
                    return;
                }
                if (empty) empty.style.display = 'none';
                if (foot) foot.hidden = false;

                list.innerHTML = cart.map(function (it) {
                    var img = it.img
                        ? '<img src="' + it.img + '" alt="">'
                        : '<div class="cart-thumb-fallback"><i class="bi bi-bag"></i></div>';
                    var link = (it.url && it.url !== '#') ? it.url : 'javascript:void(0)';
                    return '<div class="cart-item" data-id="' + it.id + '">' +
                        '<a href="' + link + '" class="cart-thumb">' + img + '</a>' +
                        '<div class="cart-item-info">' +
                            '<a href="' + link + '" class="cart-item-name">' + it.nama + '</a>' +
                            '<span class="cart-item-price">' + rupiah(it.harga) + '</span>' +
                            '<span class="cart-item-seller"><i class="bi bi-shop"></i> ' + (it.penjual || 'Penjual') + '</span>' +
                        '</div>' +
                        '<button type="button" class="cart-remove" data-id="' + it.id + '" aria-label="Hapus"><i class="bi bi-x-lg"></i></button>' +
                    '</div>';
                }).join('');

                var subtotal = cart.reduce(function (s, it) { return s + (parseFloat(it.harga) || 0); }, 0);
                var sub = document.getElementById('cartSubtotal');
                if (sub) sub.textContent = rupiah(subtotal);

                buildCheckout();
                badge();

                list.querySelectorAll('.cart-remove').forEach(function (b) {
                    b.addEventListener('click', function () { removeItem(b.getAttribute('data-id')); });
                });
            }

            function addItem(obj) {
                if (!obj.id) return;
                if (cart.some(function (it) { return String(it.id) === String(obj.id); })) return;
                cart.push(obj);
                save(cart);
                render();
            }
            function removeItem(id) {
                cart = cart.filter(function (it) { return String(it.id) !== String(id); });
                save(cart);
                render();
            }

            // Wire add-to-cart buttons (cards + detail)
            document.querySelectorAll('.add-cart-btn, .js-add-cart').forEach(function (b) {
                b.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (b.classList.contains('is-added')) return;
                    addItem({
                        id:      b.getAttribute('data-id'),
                        nama:    b.getAttribute('data-nama'),
                        harga:   parseFloat(b.getAttribute('data-harga')) || 0,
                        img:     b.getAttribute('data-img') || '',
                        url:     b.getAttribute('data-url') || '#',
                        penjual: b.getAttribute('data-penjual') || 'Penjual',
                        wa:      b.getAttribute('data-wa') || ''
                    });
                    b.classList.add('is-added');
                    var label = b.querySelector('.js-add-label');
                    if (label) {
                        var prev = label.textContent;
                        label.textContent = 'Ditambahkan';
                        setTimeout(function () { label.textContent = prev; }, 1400);
                    }
                    setTimeout(function () { b.classList.remove('is-added'); }, 1400);
                });
            });

            var clearBtn = document.getElementById('cartClear');
            if (clearBtn) clearBtn.addEventListener('click', function () {
                cart = [];
                save(cart);
                render();
            });

            render();
        })();
    </script>

    {{-- Scroll reveal engine --}}
    <script>
        (function () {
            var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var els = document.querySelectorAll('[data-reveal]');
            if (reduce || !('IntersectionObserver' in window) || !els.length) return;

            document.documentElement.classList.add('reveal-on');
            var io = new IntersectionObserver(function (entries, obs) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-revealed');
                        obs.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

            els.forEach(function (el) { io.observe(el); });
        })();
    </script>

    {{-- Global UI behaviors: theme toggle, wishlist, add-to-cart morph, parallax --}}
    <script>
        (function () {
            // --- Theme toggle ---
            var html = document.documentElement;
            var btn = document.getElementById('themeToggle');
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

            // --- Wishlist persistence + bounce ---
            var wishKey = 'bekaswit-wishlist';
            function loadWish() {
                try { return JSON.parse(localStorage.getItem(wishKey) || '[]'); }
                catch (e) { return []; }
            }
            function saveWish(arr) {
                try { localStorage.setItem(wishKey, JSON.stringify(arr)); } catch (e) {}
            }
            var wishlist = loadWish();
            document.querySelectorAll('.wishlist-btn').forEach(function (b) {
                var id = b.getAttribute('data-id');
                if (id && wishlist.indexOf(id) !== -1) b.classList.add('is-active');
                b.addEventListener('click', function (e) {
                    e.preventDefault(); e.stopPropagation();
                    var i = wishlist.indexOf(id);
                    if (i === -1) { wishlist.push(id); b.classList.add('is-active'); }
                    else { wishlist.splice(i, 1); b.classList.remove('is-active'); }
                    saveWish(wishlist);
                });
            });

            // --- Hero parallax (subtle) ---
            var bg = document.querySelector('.hero-shop .hero-bg');
            if (bg && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                var ticking = false;
                window.addEventListener('scroll', function () {
                    if (!ticking) {
                        window.requestAnimationFrame(function () {
                            var y = Math.min(window.scrollY, 600);
                            bg.style.transform = 'translateY(' + (y * 0.18) + 'px)';
                            ticking = false;
                        });
                        ticking = true;
                    }
                }, { passive: true });
            }

            // --- Filter accordion smooth toggle ---
            document.querySelectorAll('.filter-toggle').forEach(function (t) {
                t.addEventListener('click', function () {
                    var content = document.getElementById(t.getAttribute('aria-controls'));
                    if (!content) return;
                    var collapsed = content.getAttribute('data-collapsed') === 'true';
                    content.setAttribute('data-collapsed', collapsed ? 'false' : 'true');
                    t.setAttribute('aria-expanded', collapsed ? 'true' : 'false');
                });
            });

            // --- Hero banner slider ---
            var slider = document.getElementById('heroSlider');
            if (slider) {
                var slides = slider.querySelectorAll('.hero-slide');
                var dots   = slider.querySelectorAll('.hero-slider-dots button');
                var prev   = slider.querySelector('.hero-slider-nav.prev');
                var next   = slider.querySelector('.hero-slider-nav.next');
                var current = 0;
                var timer   = null;
                var INTERVAL = 6000;

                function go(idx) {
                    idx = (idx + slides.length) % slides.length;
                    slides[current].classList.remove('is-active');
                    dots[current] && dots[current].classList.remove('is-active');
                    current = idx;
                    slides[current].classList.add('is-active');
                    dots[current] && dots[current].classList.add('is-active');
                }
                function restartProgress() {
                    var bar = slider.querySelector('.hero-slider-progress::after');
                    // Force re-trigger animation by toggling a class
                    slider.classList.add('is-paused');
                    void slider.offsetWidth;
                    slider.classList.remove('is-paused');
                }
                function startAuto() {
                    stopAuto();
                    timer = setInterval(function () { go(current + 1); restartProgress(); }, INTERVAL);
                }
                function stopAuto() {
                    if (timer) { clearInterval(timer); timer = null; }
                }

                if (prev) prev.addEventListener('click', function () { go(current - 1); restartProgress(); startAuto(); });
                if (next) next.addEventListener('click', function () { go(current + 1); restartProgress(); startAuto(); });
                dots.forEach(function (d) {
                    d.addEventListener('click', function () {
                        var i = parseInt(d.getAttribute('data-index'), 10);
                        if (!isNaN(i)) { go(i); restartProgress(); startAuto(); }
                    });
                });

                slider.addEventListener('mouseenter', function () { slider.classList.add('is-paused'); stopAuto(); });
                slider.addEventListener('mouseleave', function () { slider.classList.remove('is-paused'); startAuto(); });

                // Touch swipe on mobile
                var startX = 0, deltaX = 0;
                slider.addEventListener('touchstart', function (e) {
                    startX = e.touches[0].clientX; deltaX = 0; stopAuto();
                }, { passive: true });
                slider.addEventListener('touchmove', function (e) {
                    deltaX = e.touches[0].clientX - startX;
                }, { passive: true });
                slider.addEventListener('touchend', function () {
                    if (Math.abs(deltaX) > 40) { go(deltaX < 0 ? current + 1 : current - 1); restartProgress(); }
                    startAuto();
                });

                // Pause when tab hidden
                document.addEventListener('visibilitychange', function () {
                    if (document.hidden) stopAuto(); else startAuto();
                });

                if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    startAuto();
                }
            }
        })();
    </script>

    @stack('scripts')
</body>
</html>
