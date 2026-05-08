<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Perpustakaan') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, #020817 0%, #0a0f2e 35%, #0e1647 65%, #0c1033 100%);
            min-height: 100vh;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="font-sans antialiased">

<!-- Ambient orbs -->
<div class="fixed inset-0 pointer-events-none overflow-hidden">
    <div style="position:absolute;top:-180px;left:-80px;width:550px;height:550px;background:radial-gradient(circle,rgba(59,130,246,0.14) 0%,transparent 70%);border-radius:50%;"></div>
    <div style="position:absolute;bottom:-120px;right:-80px;width:480px;height:480px;background:radial-gradient(circle,rgba(99,102,241,0.12) 0%,transparent 70%);border-radius:50%;"></div>
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:800px;height:800px;background:radial-gradient(circle,rgba(139,92,246,0.05) 0%,transparent 70%);border-radius:50%;"></div>
</div>

<div class="min-h-screen flex items-center justify-center p-4" style="position:relative;z-index:1;">
    <div style="width:100%;max-width:400px;">
        <!-- Header -->
        <div style="text-align:center;margin-bottom:32px;">
            <div style="margin-bottom:16px;display:flex;justify-content:center;">
                <img src="{{ asset('images/logo-smp.png') }}"
                     alt="Logo SMP Muhammadiyah 1 Cilacap"
                     style="width:100px;height:100px;object-fit:contain;border-radius:12px;">
            </div>
            <h1 style="color:white;font-size:22px;font-weight:700;margin-bottom:4px;">Sistem Perpustakaan</h1>
            <h2 style="color:rgba(255,255,255,0.70);font-size:14px;font-weight:400;margin-bottom:4px;">SMP Muhammadiyah 1 Cilacap</h2>
        </div>

        <!-- Card -->
        <div class="glass-strong" style="border-radius:20px;padding:32px;">
            {{ $slot }}
        </div>

        <p style="text-align:center;margin-top:20px;color:rgba(255,255,255,0.25);font-size:12px;">
            &copy; {{ date('Y') }} Perpustakaan SMP Muhammadiyah 1 Cilacap
        </p>
    </div>
</div>
</body>
</html>
