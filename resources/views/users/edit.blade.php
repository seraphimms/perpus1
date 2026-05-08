@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
<div style="max-width:600px;">
    <div class="glass" style="border-radius:16px;padding:28px;">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div style="grid-column:1/-1;">
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Nama Lengkap <span style="color:#f87171;">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $user->nama) }}"
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;">
                    @error('nama')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                </div>

                <div style="grid-column:1/-1;">
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Alamat</label>
                    <textarea name="alamat" rows="2"
                              class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;resize:vertical;">{{ old('alamat', $user->alamat) }}</textarea>
                </div>

                <div>
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $user->telepon) }}"
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;">
                </div>

                <div>
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Role</label>
                    <select name="jenis" class="glass-select"
                            style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;"
                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <option value="member" {{ old('jenis', $user->jenis)==='member' ? 'selected' : '' }}>Member</option>
                        <option value="admin" {{ old('jenis', $user->jenis)==='admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @if($user->id === auth()->id())
                    <input type="hidden" name="jenis" value="{{ $user->jenis }}">
                    @endif
                </div>

                <div style="grid-column:1/-1;">
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Email <span style="color:#f87171;">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;">
                    @error('email')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">
                        Password Baru
                        <span style="color:rgba(255,255,255,0.35);font-size:11px;font-weight:400;">(kosongkan jika tidak diubah)</span>
                    </label>
                    <input type="password" name="password"
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;">
                    @error('password')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                           class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;">
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid rgba(255,255,255,0.08);">
                <button type="submit" class="btn-primary" style="padding:10px 22px;border-radius:10px;font-size:13.5px;border:none;cursor:pointer;">Perbarui</button>
                <a href="{{ route('users.index') }}" class="btn-secondary" style="padding:10px 22px;border-radius:10px;font-size:13.5px;text-decoration:none;display:inline-block;">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
