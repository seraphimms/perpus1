@extends('layouts.app')
@section('title', 'Manajemen User')

@section('content')
<div class="page-header-row" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;">
    <form action="{{ route('users.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:8px;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
               class="glass-input" style="border-radius:10px;padding:8px 14px;font-size:13px;width:210px;">
        <select name="jenis" class="glass-select" style="border-radius:10px;padding:8px 14px;font-size:13px;min-width:140px;">
            <option value="">Semua Role</option>
            <option value="admin" {{ request('jenis')==='admin' ? 'selected' : '' }}>Admin</option>
            <option value="member" {{ request('jenis')==='member' ? 'selected' : '' }}>Member</option>
        </select>
        <button type="submit" class="btn-secondary" style="padding:8px 16px;border-radius:10px;font-size:13px;cursor:pointer;">Cari</button>
        @if(request()->hasAny(['search','jenis']))
        <a href="{{ route('users.index') }}" class="btn-secondary" style="padding:8px 16px;border-radius:10px;font-size:13px;text-decoration:none;display:inline-block;">Reset</a>
        @endif
    </form>

    <a href="{{ route('users.create') }}" class="btn-primary"
       style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:10px;font-size:13.5px;text-decoration:none;">
        <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah User
    </a>
</div>

<div class="glass" style="border-radius:16px;overflow:hidden;">
    <div class="table-responsive"><table class="glass-table" style="width:100%;border-collapse:collapse;">
        <thead>
            <tr>
                <th style="width:36px;">#</th>
                <th>Nama</th>
                <th class="hide-mobile">Email</th>
                <th class="hide-mobile">Telepon</th>
                <th style="text-align:center;">Role</th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $i => $user)
            <tr>
                <td style="color:rgba(255,255,255,0.30);">{{ $users->firstItem() + $i }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:30px;height:30px;background:linear-gradient(135deg,#3b82f6,#6366f1);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:white;flex-shrink:0;">
                            {{ strtoupper(substr($user->nama, 0, 1)) }}
                        </div>
                        <span style="color:white;font-weight:500;">{{ $user->nama }}</span>
                    </div>
                </td>
                <td class="hide-mobile">{{ $user->email }}</td>
                <td class="hide-mobile">{{ $user->telepon ?? '—' }}</td>
                <td style="text-align:center;">
                    <span class="{{ $user->jenis === 'admin' ? 'badge-purple' : 'badge-green' }}"
                          style="display:inline-block;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:500;">
                        {{ ucfirst($user->jenis) }}
                    </span>
                </td>
                <td style="text-align:center;">
                    <div style="display:flex;align-items:center;justify-content:center;gap:8px;">
                        <a href="{{ route('users.edit', $user) }}" style="font-size:12.5px;color:#93c5fd;font-weight:500;text-decoration:none;">Edit</a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('Hapus user ini?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" style="font-size:12.5px;color:#fca5a5;font-weight:500;background:none;border:none;cursor:pointer;padding:0;">Hapus</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:rgba(255,255,255,0.25);padding:40px;">Belum ada data user.</td>
            </tr>
            @endforelse
        </tbody>
    </table></div>
    @if($users->hasPages())
    <div style="padding:14px 16px;border-top:1px solid rgba(255,255,255,0.07);">{{ $users->links() }}</div>
    @endif
</div>
@endsection