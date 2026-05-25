<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Perpustakaan') }} — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body {
            background: linear-gradient(135deg, #020817 0%, #0a0f2e 30%, #0e1647 60%, #0c1033 100%);
            min-height: 100vh;
        }
        #sidebar-spacer-fix { display: block; }
        #main-sidebar {
            width: 248px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            background: rgba(7,11,35,0.80);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-right: 1px solid rgba(255,255,255,0.07);
            z-index: 50;
        }
        #hamburger-btn { display: none; }
        #sidebar-close-btn { display: none; }
        #topbar-date { display: flex; align-items: center; gap: 6px; color: rgba(255,255,255,0.40); font-size: 12px; }

        @media (max-width: 1024px) {
            /* Sidebar jadi fixed, tidak ambil ruang di flex */
            .flex.h-screen > aside { width: 0 !important; min-width: 0 !important; overflow: visible !important; }
        #main-sidebar {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                height: 100% !important;
                width: 248px !important;
                transform: translateX(-100%) !important;
                transition: transform 0.3s ease !important;
            }
            #main-sidebar.open {
                transform: translateX(0) !important;
                box-shadow: 4px 0 32px rgba(0,0,0,0.6) !important;
            }
            #hamburger-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 36px; height: 36px;
                border-radius: 10px;
                background: rgba(255,255,255,0.08);
                border: 1px solid rgba(255,255,255,0.12);
                color: rgba(255,255,255,0.80);
                cursor: pointer;
                flex-shrink: 0;
            }
            #sidebar-close-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 30px; height: 30px;
                border-radius: 8px;
                background: rgba(255,255,255,0.08);
                border: 1px solid rgba(255,255,255,0.12);
                color: rgba(255,255,255,0.70);
                cursor: pointer;
                flex-shrink: 0;
            }
            #topbar-date { display: none; }
        }
        @media (max-width: 640px) {
            #topbar { padding: 0 16px !important; height: 56px !important; }
            #main-content { padding: 16px !important; }
        }
    </style>
</head>
@stack('styles')
<body class="font-sans antialiased" style="background-attachment:fixed;">

<div class="fixed inset-0 pointer-events-none overflow-hidden" style="z-index:0;">
    <div style="position:absolute;top:-200px;left:-100px;width:600px;height:600px;background:radial-gradient(circle,rgba(59,130,246,0.12) 0%,transparent 70%);border-radius:50%;"></div>
    <div style="position:absolute;bottom:-150px;right:-100px;width:500px;height:500px;background:radial-gradient(circle,rgba(99,102,241,0.10) 0%,transparent 70%);border-radius:50%;"></div>
</div>

{{-- Alpine wrapper --}}
<div x-data="{ open: false }" class="flex h-screen overflow-hidden" style="position:relative;z-index:1;">

    {{-- Overlay --}}
    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false; document.getElementById('main-sidebar').classList.remove('open'); document.body.style.overflow='';"
         style="position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:49;backdrop-filter:blur(2px);cursor:pointer;">
    </div>

    {{-- SIDEBAR --}}
    <aside id="main-sidebar" style="position:relative;">
        <div style="padding:20px;display:flex;align-items:center;gap:12px;border-bottom:1px solid rgba(255,255,255,0.07);flex-shrink:0;">
            <img src="{{ asset('images/logo-smp.png') }}" alt="Logo"
                 style="width:38px;height:38px;object-fit:contain;border-radius:10px;flex-shrink:0;">
            <div style="flex:1;min-width:0;">
                <p style="color:white;font-weight:700;font-size:14px;line-height:1.2;">Perpustakaan</p>
                <p style="color:rgba(255,255,255,0.45);font-size:11px;">SMP Muhammadiyah 1 Cilacap</p>
            </div>
            <button id="sidebar-close-btn"
                    @click="open = false; document.getElementById('main-sidebar').classList.remove('open'); document.body.style.overflow='';"
                    aria-label="Tutup">
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav style="flex:1;overflow-y:auto;padding:7px 10px;">
            <p class="nav-section">Menu Utama</p>
            <a href="{{ route('dashboard') }}"
               @click="open=false;document.getElementById('main-sidebar').classList.remove('open');document.body.style.overflow='';"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('buku.index') }}"
               @click="open=false;document.getElementById('main-sidebar').classList.remove('open');document.body.style.overflow='';"
               class="nav-link {{ request()->routeIs('buku.*') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                {{ auth()->user()->isAdmin() ? 'Manajemen Buku' : 'Katalog Buku' }}
            </a>

            @if(!auth()->user()->isAdmin())
            <a href="{{ route('member.riwayat') }}"
               @click="open=false;document.getElementById('main-sidebar').classList.remove('open');document.body.style.overflow='';"
               class="nav-link {{ request()->routeIs('member.riwayat') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Riwayat Pinjaman
            </a>
            @endif

            @if(auth()->user()->isAdmin())
            <a href="{{ route('kategori.index') }}"
               @click="open=false;document.getElementById('main-sidebar').classList.remove('open');document.body.style.overflow='';"
               class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                Kategori
            </a>
            <a href="{{ route('users.index') }}"
               @click="open=false;document.getElementById('main-sidebar').classList.remove('open');document.body.style.overflow='';"
               class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Manajemen User
            </a>

            <p class="nav-section">Transaksi</p>
            <a href="{{ route('pinjam.index') }}"
               @click="open=false;document.getElementById('main-sidebar').classList.remove('open');document.body.style.overflow='';"
               class="nav-link {{ request()->routeIs('pinjam.*') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Peminjaman
            </a>
            <a href="{{ route('pengembalian.index') }}"
               @click="open=false;document.getElementById('main-sidebar').classList.remove('open');document.body.style.overflow='';"
               class="nav-link {{ request()->routeIs('pengembalian.*') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/></svg>
                Pengembalian
            </a>

            <p class="nav-section">Laporan</p>
            <a href="{{ route('laporan.pinjam') }}"
               @click="open=false;document.getElementById('main-sidebar').classList.remove('open');document.body.style.overflow='';"
               class="nav-link {{ request()->routeIs('laporan.pinjam') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan Peminjaman
            </a>
            <a href="{{ route('laporan.pengembalian') }}"
               @click="open=false;document.getElementById('main-sidebar').classList.remove('open');document.body.style.overflow='';"
               class="nav-link {{ request()->routeIs('laporan.pengembalian') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan Pengembalian
            </a>
            @endif

            <p class="nav-section">Akun</p>
            <a href="{{ route('profil.index') }}"
               @click="open=false;document.getElementById('main-sidebar').classList.remove('open');document.body.style.overflow='';"
               class="nav-link {{ request()->routeIs('profil.*') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Saya
            </a>
        </nav>

        <div style="padding:12px 10px;border-top:1px solid rgba(255,255,255,0.07);flex-shrink:0;">
            <div class="glass" style="border-radius:12px;padding:10px 12px;display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <div style="width:32px;height:32px;background:linear-gradient(135deg,#3b82f6,#6366f1);border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:white;flex-shrink:0;">
                    {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="color:white;font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->nama }}</p>
                    <p style="color:rgba(255,255,255,0.40);font-size:11px;text-transform:capitalize;">{{ auth()->user()->jenis }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link" style="width:100%;border:none;cursor:pointer;background:none;">
                    <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        <header id="topbar" class="glass-dark flex items-center justify-between flex-shrink-0"
                style="padding:0 28px;height:64px;border-bottom:1px solid rgba(255,255,255,0.07);">
            <div style="display:flex;align-items:center;gap:12px;">
                <button id="hamburger-btn"
                        @click="open=true; document.getElementById('main-sidebar').classList.add('open'); document.body.style.overflow='hidden';"
                        aria-label="Menu">
                    <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 style="color:white;font-size:17px;font-weight:600;">@yield('title', 'Dashboard')</h1>
                    @hasSection('subtitle')<p style="color:rgba(255,255,255,0.45);font-size:12px;margin-top:1px;">@yield('subtitle')</p>@endif
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;">
                {{-- Bell --}}
                <div style="position:relative;" x-data="{ notif: false }">
                    <button @click="notif=!notif"
                            style="width:36px;height:36px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;">
                        <svg style="width:18px;height:18px;color:rgba(255,255,255,0.70);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @if(isset($notifCount) && $notifCount > 0)
                        <span style="position:absolute;top:-4px;right:-4px;width:16px;height:16px;background:#ef4444;border-radius:50%;font-size:10px;font-weight:600;color:white;display:flex;align-items:center;justify-content:center;">
                            {{ $notifCount > 9 ? '9+' : $notifCount }}
                        </span>
                        @endif
                    </button>
                    <div x-show="notif" @click.outside="notif=false" x-cloak
                         style="position:absolute;right:0;top:44px;width:340px;max-width:calc(100vw - 32px);background:#1e2a45;border:1px solid rgba(255,255,255,0.10);border-radius:14px;overflow:hidden;z-index:200;">
                        <div style="padding:12px 16px;border-bottom:1px solid rgba(255,255,255,0.07);display:flex;align-items:center;justify-content:space-between;">
                            <p style="color:white;font-size:13px;font-weight:600;">Notifikasi</p>
                            @if(isset($notifCount) && $notifCount > 0)
                            <span style="background:rgba(239,68,68,0.15);color:#fca5a5;border:1px solid rgba(239,68,68,0.25);border-radius:20px;padding:2px 8px;font-size:11px;">{{ $notifCount }} belum dibaca</span>
                            @endif
                        </div>
                        @if(isset($notifikasi) && $notifikasi->count() > 0)
                            @foreach($notifikasi->take(5) as $notif)
                            <div style="padding:12px 16px;border-bottom:0.5px solid rgba(255,255,255,0.05);display:flex;align-items:flex-start;gap:10px;">
                                <div style="width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;{{ $notif['type']==='terlambat' ? 'background:rgba(239,68,68,0.15);' : 'background:rgba(245,158,11,0.15);' }}">
                                    <svg style="width:15px;height:15px;{{ $notif['type']==='terlambat' ? 'color:#fca5a5;' : 'color:#fbbf24;' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div style="flex:1;min-width:0;">
                                    @if(auth()->user()->isAdmin())<p style="color:white;font-size:12px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $notif['nama'] }}</p>@endif
                                    <p style="color:rgba(255,255,255,0.55);font-size:11.5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $notif['judul'] }}</p>
                                    <p style="color:rgba(255,255,255,0.30);font-size:10px;margin-top:2px;">Jatuh tempo: {{ \Carbon\Carbon::parse($notif['tgl'])->locale('id')->isoFormat('D MMM Y') }}</p>
                                </div>
                                <span style="border-radius:20px;padding:2px 8px;font-size:10px;flex-shrink:0;{{ $notif['type']==='terlambat' ? 'background:rgba(239,68,68,0.15);color:#fca5a5;border:1px solid rgba(239,68,68,0.25);' : 'background:rgba(245,158,11,0.15);color:#fbbf24;border:1px solid rgba(245,158,11,0.25);' }}">
                                    {{ $notif['type']==='terlambat' ? abs($notif['sisa']).' hari telat' : $notif['sisa'].' hari lagi' }}
                                </span>
                            </div>
                            @endforeach
                            <div style="padding:10px 16px;text-align:center;">
                                <a href="{{ route('pinjam.index') }}" style="color:#93c5fd;font-size:12px;text-decoration:none;">Lihat semua peminjaman →</a>
                            </div>
                        @else
                            <div style="padding:24px;text-align:center;">
                                <p style="color:rgba(255,255,255,0.30);font-size:12px;">Semua peminjaman on time!</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div id="topbar-date">
                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </div>
            </div>
        </header>

        @if(session('success') || session('error'))
        <div style="padding:16px 28px 0;">
            @if(session('success'))
            <div class="glass" style="border-radius:12px;padding:12px 16px;display:flex;align-items:center;gap:10px;border-color:rgba(52,211,153,0.25);background:rgba(52,211,153,0.08);">
                <svg style="width:18px;height:18px;color:#6ee7b7;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span style="color:#a7f3d0;font-size:13.5px;">{{ session('success') }}</span>
            </div>
            @endif
            @if(session('error'))
            <div class="glass" style="border-radius:12px;padding:12px 16px;display:flex;align-items:center;gap:10px;border-color:rgba(248,113,113,0.25);background:rgba(248,113,113,0.08);">
                <svg style="width:18px;height:18px;color:#fca5a5;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <span style="color:#fca5a5;font-size:13.5px;">{{ session('error') }}</span>
            </div>
            @endif
        </div>
        @endif

        <main id="main-content" class="flex-1 overflow-y-auto" style="padding:24px 28px;">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>