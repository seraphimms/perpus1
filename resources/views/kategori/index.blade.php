@extends('layouts.app')
@section('title', 'Manajemen Kategori')

@section('content')
<div style="display:flex;align-items:center;justify-content:flex-end;margin-bottom:16px;">
    @if(auth()->user()->isAdmin())
    <a href="{{ route('kategori.create') }}" class="btn-primary"
       style="display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:10px;font-size:13.5px;text-decoration:none;">
        <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Kategori
    </a>
    @endif
</div>

<div class="glass" style="border-radius:16px;overflow:hidden;">
    <table class="glass-table" style="width:100%;border-collapse:collapse;">
        <thead>
            <tr>
                <th style="width:40px;">#</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th style="text-align:center;">Jumlah Buku</th>
                @if(auth()->user()->isAdmin())
                <th style="text-align:center;">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($kategori as $i => $item)
            <tr>
                <td style="color:rgba(255,255,255,0.30);">{{ $kategori->firstItem() + $i }}</td>
                <td style="font-weight:500;color:white;">{{ $item->nama }}</td>
                <td>{{ $item->deskripsi ?? '—' }}</td>
                <td style="text-align:center;">
                    <span class="badge-blue" style="display:inline-block;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:600;">{{ $item->buku_count }}</span>
                </td>
                @if(auth()->user()->isAdmin())
                <td style="text-align:center;">
                    <div style="display:flex;align-items:center;justify-content:center;gap:8px;">
                        <a href="{{ route('kategori.edit', $item) }}"
                           style="font-size:12.5px;color:#93c5fd;font-weight:500;text-decoration:none;">Edit</a>
                        <form action="{{ route('kategori.destroy', $item) }}" method="POST"
                              onsubmit="return confirm('Hapus kategori ini?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" style="font-size:12.5px;color:#fca5a5;font-weight:500;background:none;border:none;cursor:pointer;padding:0;">Hapus</button>
                        </form>
                    </div>
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;color:rgba(255,255,255,0.25);padding:40px;">Belum ada kategori.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($kategori->hasPages())
    <div style="padding:14px 16px;border-top:1px solid rgba(255,255,255,0.07);">{{ $kategori->links() }}</div>
    @endif
</div>
@endsection
