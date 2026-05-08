@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.app')
@section('title', auth()->user()->isAdmin() ? 'Manajemen Buku' : 'Katalog Buku')

@section('content')
<div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;">
    <form action="{{ route('buku.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:8px;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, penulis, ISBN..."
               class="glass-input" style="border-radius:10px;padding:8px 14px;font-size:13px;width:220px;">
        <select name="kategori_id" class="glass-select" style="border-radius:10px;padding:8px 14px;font-size:13px;min-width:160px;">
            <option value="">Semua Kategori</option>
            @foreach($kategori as $k)
            <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-secondary" style="padding:8px 16px;border-radius:10px;font-size:13px;cursor:pointer;">Cari</button>
        @if(request()->hasAny(['search','kategori_id']))
        <a href="{{ route('buku.index') }}" class="btn-secondary" style="padding:8px 16px;border-radius:10px;font-size:13px;text-decoration:none;display:inline-block;">Reset</a>
        @endif
    </form>

    @if(auth()->user()->isAdmin())
    <a href="{{ route('buku.create') }}" class="btn-primary"
       style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:10px;font-size:13.5px;text-decoration:none;">
        <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Buku
    </a>
    @endif
</div>

<div class="glass" style="border-radius:16px;overflow:hidden;">
    <table class="glass-table" style="width:100%;border-collapse:collapse;">
        <thead>
            <tr>
                <th style="width:36px;">#</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Kategori</th>
                <th style="text-align:center;">Tahun</th>
                <th style="text-align:center;">Stok</th>
                @if(auth()->user()->isAdmin())
                <th style="text-align:center;">Proses</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($buku as $i => $item)
            <tr style="cursor:pointer;"
                onclick="showModal(
                    {{ $item->id }},
                    '{{ addslashes($item->judul) }}',
                    '{{ addslashes($item->penulis) }}',
                    '{{ addslashes($item->penerbit) }}',
                    '{{ $item->tahun }}',
                    '{{ $item->isbn ?? '-' }}',
                    '{{ $item->jumlah }}',
                    '{{ addslashes($item->kategori->nama) }}',
                    '{{ $item->cover ? Storage::url($item->cover) : '' }}',
                    '{{ addslashes($item->deskripsi ?? '') }}',
                    {{ $item->id }}
                )">
                <td style="color:rgba(255,255,255,0.30);">{{ $buku->firstItem() + $i }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        @if($item->cover)
                        <img src="{{ Storage::url($item->cover) }}" alt="Cover"
                             style="width:32px;height:42px;object-fit:cover;border-radius:4px;flex-shrink:0;">
                        @else
                        <div style="width:32px;height:42px;background:rgba(255,255,255,0.05);border-radius:4px;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                            <svg style="width:14px;height:14px;color:rgba(255,255,255,0.20);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        @endif
                        <div>
                            <p style="color:white;font-weight:500;">{{ $item->judul }}</p>
                            @if($item->isbn)<p style="color:rgba(255,255,255,0.35);font-size:11.5px;margin-top:2px;">ISBN: {{ $item->isbn }}</p>@endif
                        </div>
                    </div>
                </td>
                <td>{{ $item->penulis }}</td>
                <td>
                    <span class="badge-blue" style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:11.5px;">{{ $item->kategori->nama }}</span>
                </td>
                <td style="text-align:center;">{{ $item->tahun }}</td>
                <td style="text-align:center;">
                    <span class="{{ $item->jumlah > 0 ? 'badge-green' : 'badge-red' }}"
                          style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:50%;font-size:12px;font-weight:600;">
                        {{ $item->jumlah }}
                    </span>
                </td>
                @if(auth()->user()->isAdmin())
                <td style="text-align:center;" onclick="event.stopPropagation()">
                    <div style="display:flex;align-items:center;justify-content:center;gap:8px;">
                        <a href="{{ route('buku.edit', $item) }}" style="font-size:12.5px;color:#93c5fd;font-weight:500;text-decoration:none;">Edit</a>
                        <form action="{{ route('buku.destroy', $item) }}" method="POST"
                              onsubmit="return confirm('Hapus buku ini?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" style="font-size:12.5px;color:#fca5a5;font-weight:500;background:none;border:none;cursor:pointer;padding:0;">Hapus</button>
                        </form>
                    </div>
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;color:rgba(255,255,255,0.25);padding:40px;">Belum ada data buku.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($buku->hasPages())
    <div style="padding:14px 16px;border-top:1px solid rgba(255,255,255,0.07);">{{ $buku->links() }}</div>
    @endif
</div>

{{-- Modal Detail Buku --}}
<div id="modalBuku" onclick="closeModal()"
     style="display:none;position:fixed;inset:0;z-index:50;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
    <div onclick="event.stopPropagation()"
         style="background:#1e2a45;border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:28px;width:100%;max-width:500px;margin:16px;position:relative;max-height:90vh;overflow-y:auto;">

        {{-- Tombol Close --}}
        <button onclick="closeModal()"
                style="position:absolute;top:16px;right:16px;background:rgba(255,255,255,0.1);border:none;color:white;width:30px;height:30px;border-radius:50%;cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center;">✕</button>

        {{-- Cover --}}
        <div style="text-align:center;margin-bottom:20px;">
            <img id="modalCover" src="" alt="Cover"
                 style="max-height:200px;object-fit:contain;border-radius:10px;display:none;">
            <div id="modalNoCover"
                 style="height:140px;background:rgba(255,255,255,0.05);border-radius:10px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.25);font-size:13px;">
                Tidak ada cover
            </div>
        </div>

        {{-- Judul & Penulis --}}
        <h2 id="modalJudul" style="color:white;font-size:18px;font-weight:700;margin-bottom:4px;padding-right:32px;"></h2>
        <p id="modalPenulis" style="color:rgba(255,255,255,0.50);font-size:13px;margin-bottom:16px;"></p>

        {{-- Info Grid --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:16px;">
            <div style="background:rgba(255,255,255,0.05);border-radius:10px;padding:10px 14px;">
                <p style="color:rgba(255,255,255,0.40);font-size:11px;margin-bottom:3px;">Penerbit</p>
                <p id="modalPenerbit" style="color:white;font-size:13px;font-weight:500;"></p>
            </div>
            <div style="background:rgba(255,255,255,0.05);border-radius:10px;padding:10px 14px;">
                <p style="color:rgba(255,255,255,0.40);font-size:11px;margin-bottom:3px;">Tahun</p>
                <p id="modalTahun" style="color:white;font-size:13px;font-weight:500;"></p>
            </div>
            <div style="background:rgba(255,255,255,0.05);border-radius:10px;padding:10px 14px;">
                <p style="color:rgba(255,255,255,0.40);font-size:11px;margin-bottom:3px;">ISBN</p>
                <p id="modalIsbn" style="color:white;font-size:13px;font-weight:500;"></p>
            </div>
            <div style="background:rgba(255,255,255,0.05);border-radius:10px;padding:10px 14px;">
                <p style="color:rgba(255,255,255,0.40);font-size:11px;margin-bottom:3px;">Stok</p>
                <p id="modalStok" style="color:white;font-size:13px;font-weight:500;"></p>
            </div>
            <div style="background:rgba(255,255,255,0.05);border-radius:10px;padding:10px 14px;grid-column:1/-1;">
                <p style="color:rgba(255,255,255,0.40);font-size:11px;margin-bottom:3px;">Kategori</p>
                <p id="modalKategori" style="color:white;font-size:13px;font-weight:500;"></p>
            </div>
        </div>

        {{-- Deskripsi --}}
        <div id="modalDeskripsiWrap" style="background:rgba(255,255,255,0.05);border-radius:10px;padding:12px 14px;margin-bottom:16px;display:none;">
            <p style="color:rgba(255,255,255,0.40);font-size:11px;margin-bottom:6px;">Deskripsi</p>
            <p id="modalDeskripsi" style="color:rgba(255,255,255,0.80);font-size:13px;line-height:1.6;"></p>
        </div>

        {{-- Tombol Edit (admin only) --}}
        @if(auth()->user()->isAdmin())
        <div style="text-align:right;">
            <a id="modalEditLink" href="#" class="btn-primary"
               style="display:inline-flex;align-items:center;gap:6px;padding:9px 20px;border-radius:10px;font-size:13px;text-decoration:none;">
                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Buku
            </a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function showModal(id, judul, penulis, penerbit, tahun, isbn, stok, kategori, cover, deskripsi) {
    document.getElementById('modalJudul').textContent    = judul;
    document.getElementById('modalPenulis').textContent  = 'oleh ' + penulis;
    document.getElementById('modalPenerbit').textContent = penerbit;
    document.getElementById('modalTahun').textContent    = tahun;
    document.getElementById('modalIsbn').textContent     = isbn || '-';
    document.getElementById('modalStok').textContent     = stok + ' buku';
    document.getElementById('modalKategori').textContent = kategori;

    const img     = document.getElementById('modalCover');
    const noCover = document.getElementById('modalNoCover');
    if (cover) {
        img.src = cover;
        img.style.display    = 'block';
        noCover.style.display = 'none';
    } else {
        img.style.display    = 'none';
        noCover.style.display = 'flex';
    }

    const deskripsiWrap = document.getElementById('modalDeskripsiWrap');
    if (deskripsi) {
        document.getElementById('modalDeskripsi').textContent = deskripsi;
        deskripsiWrap.style.display = 'block';
    } else {
        deskripsiWrap.style.display = 'none';
    }

    @if(auth()->user()->isAdmin())
    document.getElementById('modalEditLink').href = '/buku/' + id + '/edit';
    @endif

    document.getElementById('modalBuku').style.display = 'flex';
}

function closeModal() {
    document.getElementById('modalBuku').style.display = 'none';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
</script>
@endpush

@endsection