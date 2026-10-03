<div>


    <style>
        .hero-toko .htxt-1 {
            color: rgba(255, 255, 255, 0.92) !important;
        }

        .hero-toko .htxt-2 {
            color: rgba(255, 255, 255, 0.55) !important;
        }

        .hero-toko .htxt-3 {
            color: rgba(255, 255, 255, 0.68) !important;
        }

        .hero-toko .btn-hero-outline {
            background: transparent;
            border: 1.5px solid rgba(255, 255, 255, 0.32);
            color: #ffffff;
        }

        .hero-toko .btn-hero-outline:hover {
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.08);
        }
    </style>

    <section class="hero-toko relative px-4 pt-14 pb-16 sm:pt-20 sm:pb-24"
        style="
        background-image: url('{{ asset('images/toko.jpeg') }}');
        
        /* ATUR ZOOM DI SINI (vw = persentase lebar layar) */
        /* Makin kecil angkanya (misal 30vw), gambar makin menjauh/zoom-out */
        /* Makin besar angkanya (misal 80vw), gambar makin mendekat/zoom-in */
        background-size: 100vw auto; 
        
        background-position: center top;
        background-attachment: fixed;
        background-repeat: no-repeat;
        background-color: #0a0a0a; /* Sisi kiri-kanan yang kosong akan berwarna gelap */
    ">

        {{-- SCRIM — Overlay gradien gelap dari kiri ke kanan agar teks tetap kontras --}}
        <div class="absolute inset-0 z-[1] pointer-events-none"
            style="background: linear-gradient(90deg, #0a0a0a 0%, rgba(10,10,10,0.92) 30%, rgba(10,10,10,0.6) 60%, rgba(10,10,10,0.2) 100%);">
        </div>

        {{-- CONTENT --}}
        <div class="relative z-[2]">

            {{-- logo kecil + identitas sistem --}}
            <div class="flex items-center gap-3 mb-8 animate-fade-up">
                <img src="{{ asset('images/logo.jpg') }}" alt="Warso Coffee"
                    class="w-12 h-12 rounded-md object-cover border-2"
                    style="border-color: var(--color-lime-500); box-shadow: var(--shadow-hard-sm);">
                <div class="leading-none">
                    <p class="text-tag htxt-1" style="font-size:12px;">Warso Coffee — Admin Panel</p>
                    <p class="font-mono text-[10px] tracking-[0.18em] uppercase htxt-2">
                        Self-Service Ordering System
                    </p>
                </div>
            </div>

            <div class="max-w-xl animate-fade-up stagger-1">
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="badge badge-lime">
                        <span class="badge-dot" style="background:var(--color-lime-400);"></span>
                        Sistem Aktif — Real-Time
                    </span>
                    <span class="badge"
                        style="background: rgba(0,217,245,0.08); color:var(--color-spray-cyan); border:1px solid rgba(0,217,245,0.3);">
                        📱 Progressive Web App
                    </span>
                </div>

                <h1 class="font-display leading-[0.92] htxt-1" style="font-size:clamp(38px,6.5vw,76px);">
                    PESANAN MASUK,
                    <span class="text-drop-lime block">TANPA ANTRE KASIR.</span>
                </h1>

                <p class="mt-5 max-w-md font-body htxt-3" style="font-size:15px;">
                    Pelanggan pesan mandiri dengan scan QR Code dari meja masing-masing,
                    bayar otomatis lewat Midtrans, dan pesanan langsung tercatat real-time
                    di panel ini — tanpa perlu dicatat manual oleh staf.
                </p>

                <div class="flex flex-wrap gap-3 mt-7">
                    <a href="#menu" class="btn btn-primary btn-lg">Monitor Menu &amp; Stok</a>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================
         DASHBOARD RINGKAS — angka operasional hari ini
         ===================================================== --}}
    <section class="px-4 py-10 relative z-10 bg-black dark:bg-[#0a0a0a]">
        <div class="page-header">
            <h2 class="font-display" style="font-size:28px; color:var(--text-title);">
                Ringkasan <span style="color:var(--color-lime-500);">Hari Ini</span>
            </h2>
            <p class="page-subtitle">Data operasional real-time dari sistem</p>
        </div>

        <div class="grid-dashboard">
            <div class="stat-card">
                <p class="stat-value">{{ $cupsToday }}</p>
                <p class="stat-label">Cup Terjual</p>
            </div>
            <div class="stat-card accent-orange">
                <p class="stat-value">{{ $ordersToday ?? 0 }}</p>
                <p class="stat-label">Pesanan Masuk</p>
            </div>
            <div class="stat-card accent-cyan">
                <p class="stat-value" style="font-size:26px;">Rp{{ number_format($revenueToday ?? 0, 0, ',', '.') }}</p>
                <p class="stat-label">Estimasi Pendapatan</p>
            </div>
            <div class="stat-card accent-pink">
                <p class="stat-value">{{ $menus->count() }}</p>
                <p class="stat-label">Menu Tersedia</p>
            </div>
        </div>
    </section>

    {{-- =====================================================
         PWA CALLOUT — ajakan install ke home screen
         ===================================================== --}}
    <section class="px-4 pb-10 relative z-10 bg-black dark:bg-[#0a0a0a]" x-data="pwaInstall()">
        <div class="card-lime flex flex-col sm:flex-row items-center justify-between gap-5 text-center sm:text-left">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-md flex items-center justify-center flex-shrink-0"
                    style="background: rgba(190,242,0,0.12); border: 1.5px solid var(--color-lime-500);">
                    <span style="font-size:22px;">📲</span>
                </div>
                <div>
                    <p class="font-display" style="font-size:20px; color:var(--text-title);">Sistem Ini PWA — Bisa
                        Diinstall</p>
                    <p class="font-mono text-xs mt-1" style="color:var(--color-ink-400);">
                        Pasang di layar HP/tablet kasir buat akses lebih cepat, tanpa buka browser tiap kali.
                    </p>
                </div>
            </div>
            <button type="button" x-show="canInstall" x-cloak @click="promptInstall()"
                class="btn btn-primary btn-lg flex-shrink-0">
                Install Aplikasi
            </button>
            <span x-show="!canInstall" x-cloak class="font-mono text-[11px]" style="color:var(--color-ink-500);">
                Buka menu browser lalu pilih "Tambahkan ke Layar Utama".
            </span>
        </div>
    </section>

    {{-- =====================================================
         KENAPA SISTEM INI DIBANGUN — feature highlight
         ===================================================== --}}
    <section class="px-4 py-10 relative z-10 bg-black dark:bg-[#0a0a0a]">
        <div class="page-header">
            <h2 class="font-display" style="font-size:28px; color:var(--text-title);">
                Kenapa Pakai <span style="color:var(--color-lime-500);">Sistem Ini?</span>
            </h2>
            <p class="page-subtitle">Menjawab kendala antrean &amp; pencatatan manual di jam sibuk</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="card animate-fade-up stagger-1">
                <span class="sticker sticker-lime mb-3">QR CODE</span>
                <p class="font-body font-semibold mt-2" style="color:var(--text-title); font-size:14px;">
                    Self-Service per Meja
                </p>
                <p class="font-mono text-[11px] mt-2" style="color:var(--color-ink-400); line-height:1.6;">
                    Pelanggan scan QR di mejanya sendiri buat lihat katalog &amp; pesan, tanpa harus antre ke kasir.
                </p>
            </div>

            <div class="card animate-fade-up stagger-2">
                <span class="sticker sticker-orange mb-3">REAL-TIME</span>
                <p class="font-body font-semibold mt-2" style="color:var(--text-title); font-size:14px;">
                    Stok &amp; Menu Live
                </p>
                <p class="font-mono text-[11px] mt-2" style="color:var(--color-ink-400); line-height:1.6;">
                    Ketersediaan menu update otomatis begitu diubah di sini — nggak ada lagi menu fisik yang basi
                    infonya.
                </p>
            </div>

            <div class="card animate-fade-up stagger-3">
                <span class="sticker sticker-cyan mb-3">MIDTRANS</span>
                <p class="font-body font-semibold mt-2" style="color:var(--text-title); font-size:14px;">
                    Pembayaran Digital
                </p>
                <p class="font-mono text-[11px] mt-2" style="color:var(--color-ink-400); line-height:1.6;">
                    QRIS &amp; e-wallet terintegrasi langsung, pesanan otomatis tercatat begitu pembayaran berhasil.
                </p>
            </div>

            <div class="card animate-fade-up stagger-4">
                <span class="sticker sticker-lime mb-3">ZERO ERROR</span>
                <p class="font-body font-semibold mt-2" style="color:var(--text-title); font-size:14px;">
                    Minim Kesalahan Catat
                </p>
                <p class="font-mono text-[11px] mt-2" style="color:var(--color-ink-400); line-height:1.6;">
                    Pesanan langsung dari perangkat pelanggan ke sistem — nggak lewat pencatatan manual staf lagi.
                </p>
            </div>
        </div>
    </section>

    {{-- =====================================================
         ALUR PEMESANAN — how it works
         ===================================================== --}}
    <section class="px-4 py-10 relative z-10 bg-black dark:bg-[#0a0a0a]">
        <div class="page-header">
            <h2 class="font-display" style="font-size:28px; color:var(--text-title);">
                Alur <span style="color:var(--color-lime-500);">Pemesanan</span>
            </h2>
            <p class="page-subtitle">Dari meja pelanggan sampai masuk ke sistem ini</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @php
                $steps = [
                    [
                        'no' => '01',
                        'title' => 'Scan QR di Meja',
                        'desc' => 'Pelanggan pindai QR Code unik yang ada di mejanya masing-masing.',
                    ],
                    [
                        'no' => '02',
                        'title' => 'Pilih & Kustomisasi',
                        'desc' => 'Katalog menu real-time terbuka di HP pelanggan, tinggal pilih & atur pesanan.',
                    ],
                    [
                        'no' => '03',
                        'title' => 'Bayar via Midtrans',
                        'desc' => 'Pembayaran diproses digital lewat QRIS/e-wallet, tanpa antre kasir.',
                    ],
                    [
                        'no' => '04',
                        'title' => 'Masuk ke Sistem',
                        'desc' => 'Pesanan otomatis tercatat di panel ini dan siap diproses staf/dapur.',
                    ],
                ];
            @endphp

            @foreach ($steps as $i => $step)
                <div class="card-hard relative animate-fade-up stagger-{{ $i + 1 }}">
                    <p class="font-display" style="font-size:34px; color:var(--color-lime-500); line-height:1;">
                        {{ $step['no'] }}</p>
                    <p class="font-body font-semibold mt-3" style="color:var(--text-title); font-size:14px;">
                        {{ $step['title'] }}
                    </p>
                    <p class="font-mono text-[11px] mt-2" style="color:var(--color-ink-400); line-height:1.6;">
                        {{ $step['desc'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- =====================================================
         MENU — monitor ketersediaan menu real-time
         ===================================================== --}}
    <section id="menu" class="px-4 py-10 relative z-10 bg-black dark:bg-[#0a0a0a]">
        <div class="page-header">
            <h2 class="font-display" style="font-size:32px; color:var(--text-title);">
                Monitor <span class="accent" style="color:var(--color-lime-500);">Menu &amp; Stok</span>
            </h2>
            <p class="page-subtitle">Status ketersediaan menu yang tampil ke pelanggan saat ini</p>
        </div>

        {{-- filter pills --}}
        <div class="option-group mb-6">
            <button type="button" wire:click="selectCategory(null)"
                class="option-pill {{ is_null($activeCategory) ? 'selected' : '' }}">
                Semua
            </button>

            @foreach ($categories as $category)
                <button type="button" wire:click="selectCategory({{ $category->id }})"
                    class="option-pill {{ $activeCategory === $category->id ? 'selected' : '' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        {{-- grid menu --}}
        @if ($menus->isEmpty())
            <div class="card text-center py-14">
                <p class="font-display" style="font-size:22px; color:var(--color-ink-200);">Belum Ada Menu Di Kategori
                    Ini</p>
                <p class="font-mono text-xs mt-2" style="color:var(--color-ink-500);">
                    Tambahkan menu baru dari halaman kelola menu.
                </p>
            </div>
        @else
            <div class="grid-menu">
                @foreach ($menus as $i => $menu)
                    <div class="card-menu animate-fade-up stagger-{{ min($i + 1, 6) }}">
                        <div class="relative">
                            <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('images/logo.jpg') }}"
                                alt="{{ $menu->name }}" class="w-full h-36 object-cover"
                                style="background: var(--color-ink-700);">

                            @if (!empty($categoryNames[$menu->category_id]))
                                <span class="badge badge-lime absolute top-2 left-2">
                                    {{ $categoryNames[$menu->category_id] }}
                                </span>
                            @endif
                        </div>

                        <div class="p-4">
                            <p class="font-body font-semibold" style="color:var(--color-ink-50); font-size:14px;">
                                {{ $menu->name }}
                            </p>

                            @if (!empty($menu->description))
                                <p class="font-mono text-[11px] mt-1 line-clamp-2"
                                    style="color:var(--color-ink-400);">
                                    {{ $menu->description }}
                                </p>
                            @endif

                            <div class="flex items-center justify-between mt-3">
                                <span class="price price-md">
                                    Rp{{ number_format($menu->price, 0, ',', '.') }}
                                </span>
                                <span
                                    class="badge {{ $menu->is_available ? 'badge-available' : 'badge-unavailable' }}">
                                    {{ $menu->is_available ? 'Ready' : 'Habis' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Alpine component: tangkap event PWA install prompt dari browser --}}
    <script>
        function pwaInstall() {
            return {
                canInstall: false,
                deferredPrompt: null,
                init() {
                    window.addEventListener('beforeinstallprompt', (e) => {
                        e.preventDefault();
                        this.deferredPrompt = e;
                        this.canInstall = true;
                    });
                },
                async promptInstall() {
                    if (!this.deferredPrompt) return;
                    this.deferredPrompt.prompt();
                    await this.deferredPrompt.userChoice;
                    this.deferredPrompt = null;
                    this.canInstall = false;
                }
            }
        }
    </script>

</div>
