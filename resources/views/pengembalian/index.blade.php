@extends('layouts.app')
@section('title', 'Transaksi Pengembalian')

@section('content')
<div class="page-header-row" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;">
    <form action="{{ route('pengembalian.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:8px;">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama anggota..."
           class="glass-input" style="border-radius:10px;padding:8px 14px;font-size:13px;width:180px;">
    <input type="date" name="dari" value="{{ request('dari') }}"
           class="glass-input" style="border-radius:10px;padding:8px 14px;font-size:13px;">
    <input type="date" name="sampai" value="{{ request('sampai') }}"
           class="glass-input" style="border-radius:10px;padding:8px 14px;font-size:13px;">
    <button type="submit" class="btn-secondary" style="padding:8px 16px;border-radius:10px;font-size:13px;cursor:pointer;">Cari</button>
    @if(request()->hasAny(['search','dari','sampai']))
    <a href="{{ route('pengembalian.index') }}" class="btn-secondary" style="padding:8px 16px;border-radius:10px;font-size:13px;text-decoration:none;display:inline-block;">Reset</a>
    @endif
</form>

    @if(auth()->user()->isAdmin())
    <a href="{{ route('pengembalian.create') }}" class="btn-success"
       style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:10px;font-size:13.5px;text-decoration:none;">
        <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Catat Pengembalian
    </a>
    @endif
</div>

<div class="glass" style="border-radius:16px;overflow:hidden;">
    <div class="table-responsive"><table class="glass-table" style="width:100%;border-collapse:collapse;">
        <thead>
            <tr>
                <th style="width:36px;">#</th>
                <th class="hide-mobile">No. Pinjam</th>
                <th>Anggota</th>
                <th class="hide-mobile">Tgl Kembali</th>
                <th style="text-align:right;">Total Denda</th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengembalian as $i => $item)
            <tr>
                <td style="color:rgba(255,255,255,0.30);">{{ $pengembalian->firstItem() + $i }}</td>
                <td class="hide-mobile" style="font-family:monospace;color:rgba(255,255,255,0.50);font-size:13px;">
                    #{{ str_pad($item->pinjam->id, 5, '0', STR_PAD_LEFT) }}
                </td>
                <td>
                    <div style="display:flex;align-items:center;gap:9px;">
                        <div style="width:28px;height:28px;background:linear-gradient(135deg,#10b981,#34d399);border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:white;flex-shrink:0;">
                            {{ strtoupper(substr($item->pinjam->user->nama,0,1)) }}
                        </div>
                        <span style="color:white;font-weight:500;">{{ $item->pinjam->user->nama }}</span>
                    </div>
                </td>
                <td class="hide-mobile">{{ $item->tgl_kembali->format('d/m/Y') }}</td>
                <td style="text-align:right;">
    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;">
        @if($item->total_denda > 0)
            <span style="font-weight:600;{{ $item->status_denda === 'lunas' ? 'color:rgba(255,255,255,0.30);text-decoration:line-through;' : 'color:#fca5a5;' }}">
                Rp {{ number_format($item->total_denda, 0, ',', '.') }}
            </span>
            @if($item->status_denda === 'lunas')
                <span style="background:rgba(52,211,153,0.15);color:#6ee7b7;border:1px solid rgba(52,211,153,0.25);border-radius:20px;padding:2px 10px;font-size:11px;">
                    Lunas
                </span>
            @else
    <button type="button"
        onclick="showKonfirmasi({{ $item->id }}, 'Rp {{ number_format($item->total_denda, 0, ',', '.') }}')"
        style="background:rgba(52,211,153,0.15);color:#6ee7b7;border:1px solid rgba(52,211,153,0.25);border-radius:20px;padding:2px 10px;font-size:11px;cursor:pointer;">
        Tandai Lunas
    </button>
    <form id="form-lunas-{{ $item->id }}" action="{{ route('pengembalian.lunas', $item) }}" method="POST" style="display:none;">
        @csrf @method('PUT')
    </form>
@endif
        @else
            <span style="color:rgba(255,255,255,0.30);">Rp 0</span>
        @endif
    </div>
</td>
                <td style="text-align:center;">
                    <a href="{{ route('pengembalian.show',$item) }}"
                       style="font-size:12.5px;color:#93c5fd;font-weight:500;text-decoration:none;">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:rgba(255,255,255,0.25);padding:40px;">
                    Belum ada data pengembalian.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table></div>
    @if($pengembalian->hasPages())
    <div style="padding:14px 16px;border-top:1px solid rgba(255,255,255,0.07);">{{ $pengembalian->links() }}</div>
    @endif
</div>
{{-- Modal Konfirmasi Lunas --}}
<div id="modalLunas" onclick="closeModalLunas()"
     style="display:none;position:fixed;inset:0;z-index:50;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
    <div onclick="event.stopPropagation()"
         style="background:#1e2a45;border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:28px;width:100%;max-width:380px;margin:16px;text-align:center;">

        <div style="width:52px;height:52px;background:rgba(52,211,153,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <svg style="width:26px;height:26px;color:#6ee7b7;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>

        <h3 style="color:white;font-size:16px;font-weight:600;margin-bottom:8px;">Tandai Denda Lunas?</h3>
        <p style="color:rgba(255,255,255,0.45);font-size:13px;margin-bottom:6px;">Denda sebesar</p>
        <p id="modalDendaAmount" style="color:#6ee7b7;font-size:20px;font-weight:700;margin-bottom:16px;"></p>
        <p style="color:rgba(255,255,255,0.35);font-size:12px;margin-bottom:24px;">akan ditandai sudah dibayar. Data denda tetap tersimpan.</p>

        <div style="display:flex;gap:10px;justify-content:center;">
            <button onclick="closeModalLunas()" class="btn-secondary"
                    style="padding:9px 22px;border-radius:10px;font-size:13px;cursor:pointer;">
                Batal
            </button>
            <button id="btnKonfirmasiLunas" onclick="submitLunas()"
                    style="background:linear-gradient(135deg,#10b981,#34d399);color:white;border:none;padding:9px 22px;border-radius:10px;font-size:13px;font-weight:500;cursor:pointer;">
                Ya, Tandai Lunas
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentFormId = null;

function showKonfirmasi(id, amount) {
    currentFormId = id;
    document.getElementById('modalDendaAmount').textContent = amount;
    document.getElementById('modalLunas').style.display = 'flex';
}

function closeModalLunas() {
    document.getElementById('modalLunas').style.display = 'none';
    currentFormId = null;
}

function submitLunas() {
    if (currentFormId) {
        const form = document.getElementById('form-lunas-' + currentFormId);
        if (form) {
            form.submit();
        } else {
            console.log('Form tidak ditemukan: form-lunas-' + currentFormId);
        }
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModalLunas();
});
</script>
@endpush
@endsection