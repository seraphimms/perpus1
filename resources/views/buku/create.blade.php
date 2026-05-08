@extends('layouts.app')
@section('title', 'Tambah Buku')

@section('content')
<div style="max-width:680px;">
    <div class="glass" style="border-radius:16px;padding:28px;">
        <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:0;">
                <div style="grid-column:1/-1;margin-bottom:4px;">
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Judul Buku <span style="color:#f87171;">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul') }}"
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;">
                    @error('judul')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Penulis <span style="color:#f87171;">*</span></label>
                    <input type="text" name="penulis" value="{{ old('penulis') }}"
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;">
                    @error('penulis')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Penerbit <span style="color:#f87171;">*</span></label>
                    <input type="text" name="penerbit" value="{{ old('penerbit') }}"
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;">
                    @error('penerbit')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Tahun Terbit <span style="color:#f87171;">*</span></label>
                    <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" min="1900" max="{{ date('Y') + 1 }}"
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;">
                    @error('tahun')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}"
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;">
                    @error('isbn')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Jumlah Stok <span style="color:#f87171;">*</span></label>
                    <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="1"
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;">
                    @error('jumlah')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                </div>

                <div style="grid-column:1/-1;">
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Kategori <span style="color:#f87171;">*</span></label>
                    <select name="kategori_id" class="glass-select"
                            style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $k)
                        <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                </div>
                <div style="grid-column:1/-1;">
    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Cover Buku</label>
    <label for="cover" style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;border:2px dashed rgba(255,255,255,0.15);border-radius:12px;padding:24px;cursor:pointer;transition:border-color 0.2s;" 
       onmouseover="this.style.borderColor='rgba(99,102,241,0.6)'" 
       onmouseout="this.style.borderColor='rgba(255,255,255,0.15)'">
    <svg style="width:36px;height:36px;color:rgba(255,255,255,0.35);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
    </svg>
    <div style="text-align:center;">
        <p style="color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;">Klik untuk upload cover</p>
        <p style="color:rgba(255,255,255,0.35);font-size:12px;margin-top:2px;">JPG, PNG, WEBP — Maks 2MB</p>
    </div>
    <p id="coverFileName" style="color:#93c5fd;font-size:12px;display:none;"></p>
    <input type="file" id="cover" name="cover" accept="image/*" style="display:none;"
           onchange="document.getElementById('coverFileName').textContent=this.files[0].name; document.getElementById('coverFileName').style.display='block';">
</label>
    <p style="margin-top:5px;font-size:11.5px;color:rgba(255,255,255,0.35);">Format: JPG, PNG, WEBP. Maks 2MB.</p>
    @error('cover')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
</div>

<div style="grid-column:1/-1;">
    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Deskripsi</label>
    <textarea name="deskripsi" rows="4"
              class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;resize:vertical;">{{ old('deskripsi') }}</textarea>
    @error('deskripsi')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
</div>
            </div>

            <div style="display:flex;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid rgba(255,255,255,0.08);">
                <button type="submit" class="btn-primary" style="padding:10px 22px;border-radius:10px;font-size:13.5px;border:none;cursor:pointer;">Simpan</button>
                <a href="{{ route('buku.index') }}" class="btn-secondary" style="padding:10px 22px;border-radius:10px;font-size:13.5px;text-decoration:none;display:inline-block;">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
