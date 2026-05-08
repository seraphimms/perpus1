@extends('layouts.app')
@section('title', 'Tambah Kategori')

@section('content')
<div style="max-width:520px;">
    <div class="glass" style="border-radius:16px;padding:28px;">
        <form action="{{ route('kategori.store') }}" method="POST">
            @csrf

            <div style="margin-bottom:18px;">
                <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">
                    Nama Kategori <span style="color:#f87171;">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                       class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;">
                @error('nama')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;resize:vertical;">{{ old('deskripsi') }}</textarea>
            </div>

            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn-primary" style="padding:10px 22px;border-radius:10px;font-size:13.5px;border:none;cursor:pointer;">Simpan</button>
                <a href="{{ route('kategori.index') }}" class="btn-secondary" style="padding:10px 22px;border-radius:10px;font-size:13.5px;text-decoration:none;display:inline-block;">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
