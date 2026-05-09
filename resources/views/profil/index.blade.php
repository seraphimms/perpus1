@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div style="max-width:640px;">

    {{-- Header profil --}}
    <div class="glass" style="border-radius:16px;padding:24px;margin-bottom:16px;display:flex;align-items:center;gap:16px;">
        <div style="width:60px;height:60px;background:linear-gradient(135deg,#3b82f6,#6366f1);border-radius:16px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:24px;color:white;flex-shrink:0;">
            {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
        </div>
        <div>
            <h2 style="color:white;font-size:18px;font-weight:700;">{{ auth()->user()->nama }}</h2>
            <p style="color:rgba(255,255,255,0.45);font-size:13px;margin-top:2px;">{{ auth()->user()->email }}</p>
            <span style="display:inline-block;margin-top:6px;background:rgba(99,102,241,0.15);color:#a5b4fc;border:1px solid rgba(99,102,241,0.25);border-radius:20px;padding:2px 10px;font-size:11px;text-transform:capitalize;">
                {{ auth()->user()->jenis }}
            </span>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success') && !session('tab'))
    <div style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);color:#86efac;border-radius:10px;padding:12px 14px;font-size:13px;margin-bottom:16px;">
        ✅ {{ session('success') }}
    </div>
    @endif

    {{-- Form Edit Profil --}}
    <div class="glass" style="border-radius:16px;padding:28px;margin-bottom:16px;">
        <h3 style="color:white;font-size:15px;font-weight:600;margin-bottom:20px;">Edit Informasi Profil</h3>

        <form action="{{ route('profil.update') }}" method="POST">
            @csrf @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div style="grid-column:1/-1;">
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Nama Lengkap <span style="color:#f87171;">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $user->nama) }}"
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;">
                    @error('nama')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                </div>

                <div style="grid-column:1/-1;">
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Email <span style="color:#f87171;">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;">
                    @error('email')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Nomor Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $user->telepon) }}"
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;"
                           placeholder="08xx-xxxx-xxxx">
                    @error('telepon')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Jenis</label>
                    <input type="text" value="{{ ucfirst($user->jenis) }}" disabled
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;opacity:0.5;cursor:not-allowed;">
                </div>

                <div style="grid-column:1/-1;">
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Alamat</label>
                    <textarea name="alamat" rows="3"
                              class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;resize:vertical;">{{ old('alamat', $user->alamat) }}</textarea>
                    @error('alamat')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                </div>
            </div>

            <div style="margin-top:24px;padding-top:20px;border-top:1px solid rgba(255,255,255,0.08);">
                <button type="submit" class="btn-primary" style="padding:10px 22px;border-radius:10px;font-size:13.5px;border:none;cursor:pointer;">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Form Ganti Password --}}
    <div class="glass" style="border-radius:16px;padding:28px;">
        <h3 style="color:white;font-size:15px;font-weight:600;margin-bottom:20px;">Ganti Password</h3>

        @if(session('success') && session('tab') == 'password')
        <div style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);color:#86efac;border-radius:10px;padding:12px 14px;font-size:13px;margin-bottom:16px;">
            ✅ {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('profil.password') }}" method="POST">
            @csrf @method('PUT')

            <div style="display:flex;flex-direction:column;gap:16px;">
                <div>
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Password Lama <span style="color:#f87171;">*</span></label>
                    <input type="password" name="password_lama"
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;">
                    @error('password_lama')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Password Baru <span style="color:#f87171;">*</span></label>
                    <input type="password" name="password"
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;"
                           placeholder="Minimal 8 karakter">
                    @error('password')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Konfirmasi Password Baru <span style="color:#f87171;">*</span></label>
                    <input type="password" name="password_confirmation"
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;"
                           placeholder="Ulangi password baru">
                </div>
            </div>

            <div style="margin-top:24px;padding-top:20px;border-top:1px solid rgba(255,255,255,0.08);">
                <button type="submit" class="btn-primary" style="padding:10px 22px;border-radius:10px;font-size:13.5px;border:none;cursor:pointer;">
                    Ganti Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection