@extends('layouts.app')
@section('title', 'Import Buku')

@section('content')
<div style="max-width:600px;">
    <div class="glass" style="border-radius:16px;padding:28px;margin-bottom:16px;">
        <h3 style="color:white;font-size:15px;font-weight:600;margin-bottom:6px;">Download Template</h3>
        <p style="color:rgba(255,255,255,0.45);font-size:13px;margin-bottom:16px;">
            Download template CSV, isi dengan data buku, lalu upload kembali.
            Kolom <code style="background:rgba(255,255,255,0.1);padding:1px 6px;border-radius:4px;">kategori</code>
            harus sesuai dengan nama kategori yang sudah ada.
        </p>

        {{-- Daftar kategori yang tersedia --}}
        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:12px 14px;margin-bottom:16px;">
            <p style="color:rgba(255,255,255,0.40);font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:8px;">Kategori tersedia</p>
            <div style="display:flex;flex-wrap:wrap;gap:6px;">
                @foreach(\App\Models\Kategori::all() as $k)
                <span style="background:rgba(99,102,241,0.15);color:#a5b4fc;border:1px solid rgba(99,102,241,0.25);border-radius:20px;padding:3px 12px;font-size:12px;">{{ $k->nama }}</span>
                @endforeach
            </div>
        </div>

        <a href="{{ route('buku.template') }}" class="btn-secondary"
           style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:10px;font-size:13px;text-decoration:none;">
            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Download Template CSV
        </a>
    </div>

    <div class="glass" style="border-radius:16px;padding:28px;">
        <h3 style="color:white;font-size:15px;font-weight:600;margin-bottom:6px;">Upload File</h3>
        <p style="color:rgba(255,255,255,0.45);font-size:13px;margin-bottom:20px;">Upload file Excel (.xlsx) atau CSV yang sudah diisi.</p>

        @if(session('success'))
        <div style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);color:#86efac;border-radius:10px;padding:12px 14px;font-size:13px;margin-bottom:16px;">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;border-radius:10px;padding:12px 14px;font-size:13px;margin-bottom:16px;">
            ⚠️ {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('buku.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="file" id="fileLabel"
                   style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;border:2px dashed rgba(255,255,255,0.15);border-radius:12px;padding:28px;cursor:pointer;transition:border-color 0.2s;"
                   onmouseover="this.style.borderColor='rgba(99,102,241,0.6)'"
                   onmouseout="this.style.borderColor='rgba(255,255,255,0.15)'">
                <svg style="width:36px;height:36px;color:rgba(255,255,255,0.35);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <div style="text-align:center;">
                    <p style="color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;">Klik untuk pilih file</p>
                    <p style="color:rgba(255,255,255,0.35);font-size:12px;margin-top:2px;">XLSX atau CSV — Maks 5MB</p>
                </div>
                <p id="fileName" style="color:#93c5fd;font-size:12px;display:none;"></p>
                <input type="file" id="file" name="file" accept=".xlsx,.csv" style="display:none;"
                       onchange="document.getElementById('fileName').textContent=this.files[0].name;document.getElementById('fileName').style.display='block';">
            </label>

            <div style="display:flex;gap:10px;margin-top:20px;padding-top:20px;border-top:1px solid rgba(255,255,255,0.08);">
                <button type="submit" class="btn-primary" style="padding:10px 22px;border-radius:10px;font-size:13.5px;border:none;cursor:pointer;">
                    Import Sekarang
                </button>
                <a href="{{ route('buku.index') }}" class="btn-secondary" style="padding:10px 22px;border-radius:10px;font-size:13.5px;text-decoration:none;display:inline-block;">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection