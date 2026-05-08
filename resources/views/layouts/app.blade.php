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
        body {
            background: linear-gradient(135deg, #020817 0%, #0a0f2e 30%, #0e1647 60%, #0c1033 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="font-sans antialiased" style="background-attachment:fixed;">

<!-- Ambient background orbs -->
<div class="fixed inset-0 pointer-events-none overflow-hidden" style="z-index:0;">
    <div style="position:absolute;top:-200px;left:-100px;width:600px;height:600px;background:radial-gradient(circle,rgba(59,130,246,0.12) 0%,transparent 70%);border-radius:50%;"></div>
    <div style="position:absolute;bottom:-150px;right:-100px;width:500px;height:500px;background:radial-gradient(circle,rgba(99,102,241,0.10) 0%,transparent 70%);border-radius:50%;"></div>
    <div style="position:absolute;top:40%;left:40%;width:400px;height:400px;background:radial-gradient(circle,rgba(139,92,246,0.07) 0%,transparent 70%);border-radius:50%;"></div>
</div>

<div class="flex h-screen overflow-hidden" style="position:relative;z-index:1;">

    {{-- ── SIDEBAR ── --}}
    <aside class="glass-sidebar flex flex-col flex-shrink-0" style="width:248px;">
        {{-- Logo --}}
        <div class="p-5 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.07);">
            <img src="{{ asset('images/logo-smp.png') }}"
                 alt="Logo SMP Muh 1 Cilacap"
                 style="width:38px;height:38px;object-fit:contain;border-radius:10px;">
            <div>
                <p style="color:white;font-weight:700;font-size:14px;line-height:1.2;">Perpustakaan</p>
                <p style="color:rgba(255,255,255,0.45);font-size:11px;">SMP Muhammadiyah 1 Cilacap</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto" style="padding:12px 10px;">
            <p class="nav-section">Menu Utama</p>

            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            <a href="{{ route('buku.index') }}"
               class="nav-link {{ request()->routeIs('buku.*') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                {{ auth()->user()->isAdmin() ? 'Manajemen Buku' : 'Katalog Buku' }}
            </a>

            @if(auth()->user()->isAdmin())
            <a href="{{ route('kategori.index') }}"
               class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                Kategori
            </a>

            <a href="{{ route('users.index') }}"
               class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Manajemen User
            </a>

            <p class="nav-section">Transaksi</p>

            <a href="{{ route('pinjam.index') }}"
               class="nav-link {{ request()->routeIs('pinjam.*') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Peminjaman
            </a>

            <a href="{{ route('pengembalian.index') }}"
               class="nav-link {{ request()->routeIs('pengembalian.*') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/></svg>
                Pengembalian
            </a>

            <p class="nav-section">Laporan</p>

            <a href="{{ route('laporan.pinjam') }}"
               class="nav-link {{ request()->routeIs('laporan.pinjam') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan Peminjaman
            </a>

            <a href="{{ route('laporan.pengembalian') }}"
               class="nav-link {{ request()->routeIs('laporan.pengembalian') ? 'active' : '' }}">
                <svg style="width:17px;height:17px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan Pengembalian
            </a>
            @endif
        </nav>

        {{-- User Profile & Logout --}}
        <div style="padding:12px 10px;border-top:1px solid rgba(255,255,255,0.07);">
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

    {{-- ── MAIN CONTENT ── --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Topbar --}}
        <header class="glass-dark flex items-center justify-between flex-shrink-0" style="padding:0 28px;height:64px;border-bottom:1px solid rgba(255,255,255,0.07);">
            <div>
                <h1 style="color:white;font-size:17px;font-weight:600;">@yield('title', 'Dashboard')</h1>
                @hasSection('subtitle')<p style="color:rgba(255,255,255,0.45);font-size:12px;margin-top:1px;">@yield('subtitle')</p>@endif
            </div>
            <div style="display:flex;align-items:center;gap:6px;color:rgba(255,255,255,0.40);font-size:12px;">
                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ now()->isoFormat('dddd, D MMMM Y') }}
            </div>
        </header>

        {{-- Flash Messages --}}
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

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto" style="padding:24px 28px;">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
