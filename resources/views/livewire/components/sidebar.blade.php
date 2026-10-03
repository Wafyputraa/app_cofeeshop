<div wire:poll.5s="refreshCounts">
    @auth
        <div x-data="{
            isOpen: false,
            notifOpen: false,
            prevCount: {{ $pendingCount }},
            soundEnabled: localStorage.getItem('wc_sound') !== 'false',
        
            init() {
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission();
                }
            },
        
            toggleSound() {
                this.soundEnabled = !this.soundEnabled;
                localStorage.setItem('wc_sound', this.soundEnabled);
            },
        
            playBeep() {
                if (!this.soundEnabled) return;
                try {
                    const ctx = new(window.AudioContext || window.webkitAudioContext)();
                    [880, 660, 990].forEach((freq, i) => {
                        const o = ctx.createOscillator(),
                            g = ctx.createGain();
                        o.connect(g);
                        g.connect(ctx.destination);
                        o.type = 'sine';
                        o.frequency.value = freq;
                        const t = ctx.currentTime + i * 0.13;
                        g.gain.setValueAtTime(0.25, t);
                        g.gain.exponentialRampToValueAtTime(0.001, t + 0.25);
                        o.start(t);
                        o.stop(t + 0.3);
                    });
                } catch (e) {}
            },
        
            sendBrowserNotif(count) {
                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification('🔥 Warso Coffee', {
                        body: count + ' pesanan baru menunggu dibuat!',
                        icon: '/favicon.ico',
                        tag: 'warso-order',
                    });
                }
            },
        
            checkNewOrders(newCount) {
                if (newCount > this.prevCount) {
                    this.playBeep();
                    this.sendBrowserNotif(newCount);
                    this.notifOpen = true;
                }
                this.prevCount = newCount;
            }
        }" x-init="init()" x-effect="checkNewOrders({{ $pendingCount }})"
            x-on:livewire:navigated.window="isOpen = false">


            {{-- ══ MOBILE TOPBAR ══ --}}
            <header id="mobile-topbar" class="flex lg:hidden"
                style="
    position: fixed; top: 0; left: 0; right: 0;
    height: 56px;
    background: var(--color-ink-950);
    border-bottom: 1px solid var(--color-ink-700);
    align-items: center; justify-content: space-between;
    padding: 0 16px; z-index: 40;
">
                <button @click="isOpen = !isOpen" class="topbar-icon-btn" title="Toggle Menu">
                    <template x-if="isOpen">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </template>
                    <template x-if="!isOpen">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                    </template>
                </button>

                <div class="mobile-logo">WARSO<span style="color:var(--color-lime-500);">.</span></div>

                <div style="position:relative; flex-shrink:0;">
                    @can('admin')
                        <button @click="notifOpen = !notifOpen" class="topbar-icon-btn" title="Notifikasi">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                            </svg>
                        </button>
                        @if ($pendingCount > 0)
                            <span class="notif-badge">{{ $pendingCount > 9 ? '9+' : $pendingCount }}</span>
                        @endif
                    @endcan
                </div>
            </header>

            <script>
                (function() {
                    function syncTopbar() {
                        var t = document.getElementById('mobile-topbar');
                        if (t) t.style.display = window.innerWidth < 1024 ? 'flex' : 'none';
                    }
                    syncTopbar();
                    window.addEventListener('resize', syncTopbar);
                    document.addEventListener('livewire:navigated', syncTopbar);
                })();
            </script>

            {{-- Mobile overlay --}}
            <div x-show="isOpen" x-transition.opacity @click="isOpen = false" x-cloak
                style="position:fixed;inset:0;background:rgba(0,0,0,0.75);z-index:35;backdrop-filter:blur(2px);"
                class="lg:hidden"></div>

            {{-- ══ SIDEBAR ══ --}}
            <aside class="sidebar" :class="isOpen ? 'open' : ''" id="main-sidebar">

                <div class="sidebar-header" style="align-items:flex-start; position:relative;">
                    {{-- Logo + brand teks --}}
                    <div style="display:flex; align-items:center; gap:10px; flex:1; min-width:0;">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Warso Coffee"
                            style="width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid var(--color-lime-500); box-shadow:var(--shadow-hard-sm); flex-shrink:0;">
                        <div style="display:flex; flex-direction:column; min-width:0; line-height:1.2;">
                            <div class="sidebar-logo-mark" style="white-space:nowrap;">WARSO<span class="accent">. Coffee</span></div>
                            <div class="sidebar-logo-sub">WarungSlow</div>
                        </div>
                    </div>

                    {{-- Bell: absolut pojok kanan bawah --}}
                    <div style="position:absolute; bottom:0; right:0; flex-shrink:0;">
                        @can('admin')
                            <button @click="notifOpen = !notifOpen" class="sidebar-bell-btn" title="Notifikasi"
                                :class="{ 'bell-ring': {{ $pendingCount }} > 0 }"
                                style="width:26px; height:26px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                                </svg>
                            </button>
                            @if ($pendingCount > 0)
                                <span class="notif-badge">{{ $pendingCount > 9 ? '9+' : $pendingCount }}</span>
                            @endif
                        @endcan
                    </div>
                </div>



                <nav class="sidebar-nav">
                    @can('kepala')
                        <div class="sidebar-section">Overview</div>
                        <a wire:navigate href="{{ route('dashboard') }}"
                            class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7" />
                                <rect x="14" y="3" width="7" height="7" />
                                <rect x="14" y="14" width="7" height="7" />
                                <rect x="3" y="14" width="7" height="7" />
                            </svg>
                            Dashboard
                        </a>
                    @endcan

                    @can('admin')
                        <div class="sidebar-section">Pesanan</div>
                        <a wire:navigate href="{{ route('orders.index') }}"
                            class="nav-item {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                            </svg>
                            Live Orders
                            @if ($pendingCount > 0)
                                <span class="nav-badge">{{ $pendingCount }}</span>
                            @endif
                        </a>
                        <a wire:navigate href="{{ route('riwayat.index') }}"
                            class="nav-item {{ request()->routeIs('riwayat.index') ? 'active' : '' }}">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            Riwayat
                        </a>

                        <div class="sidebar-section">Katalog</div>
                        <a wire:navigate href="{{ route('menu.index') }}"
                            class="nav-item {{ request()->routeIs('menu.*') ? 'active' : '' }}">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M17 8h1a4 4 0 0 1 0 8h-1" />
                                <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z" />
                                <line x1="6" y1="2" x2="6" y2="4" />
                                <line x1="10" y1="2" x2="10" y2="4" />
                                <line x1="14" y1="2" x2="14" y2="4" />
                            </svg>
                            Menu
                        </a>
                        <a wire:navigate href="{{ route('categories.index') }}"
                            class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M4 6h16M4 10h16M4 14h10M4 18h6" />
                            </svg>
                            Kategori
                        </a>
                    @endcan

                    <div class="sidebar-section">Pengaturan</div>
                    @can('admin')
                        <a wire:navigate href="{{ route('meja.index') }}"
                            class="nav-item {{ request()->routeIs('meja.index*') ? 'active' : '' }}">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                <path d="M3 9h18M3 15h18M9 3v18M15 3v18" />
                            </svg>
                            Manajemen Meja
                        </a>
                    @endcan

                    @can('kepala')
                        <a wire:navigate href="{{ route('report.index') }}"
                            class="nav-item {{ request()->routeIs('report.index*') ? 'active' : '' }}">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="20" x2="18" y2="10" />
                                <line x1="12" y1="20" x2="12" y2="4" />
                                <line x1="6" y1="20" x2="6" y2="14" />
                                <line x1="2" y1="20" x2="22" y2="20" />
                            </svg>
                            Laporan Penjualan
                        </a>
                    @endcan
                </nav>

                <div class="sidebar-footer">
                    {{-- <div class="sidebar-theme-wrap">
                        @livewire('components.theme-toggle')
                    </div> --}}
                    <div class="sidebar-user">
                        <div class="sidebar-user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="sidebar-user-info">
                            <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                            <div class="sidebar-user-role">Barista</div>
                        </div>
                        <button wire:click="logout" title="Logout" class="sidebar-logout-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" y1="12" x2="9" y2="12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </aside>

            {{-- ══ NOTIF DROPDOWN ══ --}}
            @can('admin')
                <div x-show="notifOpen" x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100" x-transition:leave-end="opacity-0"
                    @click.outside="notifOpen = false" x-cloak
                    style="
                        position: fixed;
                        top: 64px; left: 16px;
                        width: 300px;
                        background: var(--color-ink-800);
                        border: 1.5px solid var(--color-ink-500);
                        border-radius: var(--radius-lg);
                        box-shadow: 6px 6px 0px var(--color-ink-950);
                        z-index: 100;
                    ">
                    {{-- Header --}}
                    <div
                        style="padding:10px 14px; border-bottom:1px solid var(--color-ink-700); display:flex; align-items:center; justify-content:space-between;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span class="font-display" style="font-size:14px; letter-spacing:0.05em; color:#fff;">PESANAN
                                BARU</span>
                            @if ($pendingCount > 0)
                                <span class="badge badge-orange">{{ $pendingCount }}</span>
                            @endif
                        </div>
                        <div style="display:flex; gap:5px;">
                            {{-- Toggle sound --}}
                            <button @click="toggleSound()" class="sidebar-bell-btn" style="width:26px;height:26px;"
                                :title="soundEnabled ? 'Matikan suara' : 'Aktifkan suara'">
                                <template x-if="soundEnabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" />
                                        <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07" />
                                    </svg>
                                </template>
                                <template x-if="!soundEnabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" />
                                        <line x1="23" y1="9" x2="17" y2="15" />
                                        <line x1="17" y1="9" x2="23" y2="15" />
                                    </svg>
                                </template>
                            </button>
                            <button @click="notifOpen = false" class="sidebar-bell-btn" style="width:26px;height:26px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2.5">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- List --}}
                    <div style="padding:8px; max-height:280px; overflow-y:auto;">
                        @forelse($latestOrders as $order)
                            <a wire:navigate href="{{ route('orders.index') }}" @click="notifOpen = false"
                                style="
                                    display:flex; align-items:flex-start; gap:10px;
                                    padding:10px; border-radius:var(--radius-md);
                                    background:var(--color-ink-900);
                                    border:1px solid var(--color-ink-600);
                                    margin-bottom:6px; text-decoration:none;
                                    transition: border-color 0.15s;
                                "
                                onmouseover="this.style.borderColor='var(--color-orange-500)'"
                                onmouseout="this.style.borderColor='var(--color-ink-600)'">
                                <div
                                    style="width:34px;height:34px;background:rgba(240,82,0,0.12);border:1px solid rgba(240,82,0,0.3);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="var(--color-orange-400)"
                                        stroke-width="2">
                                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                                        <line x1="3" y1="6" x2="21" y2="6" />
                                        <path d="M16 10a4 4 0 0 1-8 0" />
                                    </svg>
                                </div>
                                <div style="flex:1; min-width:0;">
                                    <div style="display:flex; align-items:center; gap:5px; margin-bottom:2px;">
                                        <span class="order-tag" style="font-size:11px;">#{{ $order['order_code'] }}</span>
                                        <span class="table-indicator"
                                            style="font-size:9px; padding:2px 6px;">T{{ $order['table'] }}</span>
                                    </div>
                                    <div class="price price-sm" style="font-size:12px;">Rp
                                        {{ number_format($order['total'], 0, ',', '.') }}</div>
                                    <div class="font-mono"
                                        style="font-size:10px; color:var(--color-ink-400); margin-top:1px;">
                                        {{ $order['ago'] }}</div>
                                </div>
                                <span class="badge badge-pending" style="flex-shrink:0; margin-top:2px;"><span
                                        class="badge-dot"></span>NEW</span>
                            </a>
                        @empty
                            <p class="font-mono"
                                style="font-size:11px; color:var(--color-ink-500); text-align:center; padding:20px 0;">
                                Tidak ada pesanan pending
                            </p>
                        @endforelse
                    </div>

                    @if ($pendingCount > 3)
                        <div style="padding:8px 12px; border-top:1px solid var(--color-ink-700);">
                            <a wire:navigate href="{{ route('orders.index') }}" @click="notifOpen = false"
                                class="btn btn-cta btn-sm btn-block">
                                Lihat Semua {{ $pendingCount }} Pesanan →
                            </a>
                        </div>
                    @endif
                </div>
            @endcan

        </div>
    @endauth
</div>

<style>
    @keyframes bell-ring {

        0%,
        100% {
            transform: rotate(0)
        }

        15% {
            transform: rotate(12deg)
        }

        30% {
            transform: rotate(-12deg)
        }

        45% {
            transform: rotate(7deg)
        }

        60% {
            transform: rotate(-7deg)
        }

        75% {
            transform: rotate(3deg)
        }
    }

    .bell-ring svg {
        animation: bell-ring 1.5s ease infinite;
        transform-origin: top center;
        display: block;
    }
</style>
