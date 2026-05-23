@extends('layouts.app')
@section('title', 'Detail Pengembalian')

@section('content')
<div style="max-width:680px;display:flex;flex-direction:column;gap:16px;">

    {{-- Info Pengembalian --}}
    <div class="glass" style="border-radius:16px;padding:24px;">
        <p style="color:rgba(255,255,255,0.40);font-size:11px;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;margin-bottom:16px;">Informasi Pengembalian</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;row-gap:14px;">
            <div>
                <p style="color:rgba(255,255,255,0.40);font-size:12px;margin-bottom:3px;">No. Transaksi Pinjam</p>
                <p style="color:white;font-weight:600;font-family:monospace;">#{{ str_pad($pengembalian->pinjam->id,5,'0',STR_PAD_LEFT) }}</p>
            </div>
            <div>
                <p style="color:rgba(255,255,255,0.40);font-size:12px;margin-bottom:3px;">Tanggal Kembali</p>
                <p style="color:white;font-weight:500;">{{ $pengembalian->tgl_kembali->format('d/m/Y') }}</p>
            </div>
            <div>
                <p style="color:rgba(255,255,255,0.40);font-size:12px;margin-bottom:3px;">Anggota</p>
                <p style="color:white;font-weight:500;">{{ $pengembalian->pinjam->user->nama }}</p>
            </div>
            <div>
                <p style="color:rgba(255,255,255,0.40);font-size:12px;margin-bottom:3px;">Total Denda</p>
                <p style="font-weight:700;font-size:20px;{{ $pengembalian->total_denda > 0 ? 'color:#fca5a5;' : 'color:#6ee7b7;' }}">
                    Rp {{ number_format($pengembalian->total_denda, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Rincian Per Buku --}}
    <div class="glass" style="border-radius:16px;overflow:hidden;">
        <div style="padding:16px 20px;border-bottom:1px solid rgba(255,255,255,0.07);">
            <p style="color:rgba(255,255,255,0.40);font-size:11px;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;">Rincian Per Buku</p>
        </div>
        <table class="glass-table" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th style="text-align:center;">Kondisi</th>
                    <th style="text-align:center;">Status Penggantian</th>
                    <th>Tgl Kembali</th>
                    <th style="text-align:right;">Denda</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengembalian->detailPengembalian as $dp)
                <tr>
                    <td style="color:white;font-weight:500;">{{ $dp->detailPinjam->buku->judul }}</td>
                    <td style="text-align:center;">
                        @php
                            $kondisiClass = match($dp->kondisi_buku) {
                                'baik'   => 'badge-green',
                                'rusak'  => 'badge-yellow',
                                'hilang' => 'badge-red',
                                default  => 'badge-blue'
                            };
                        @endphp
                        <span class="{{ $kondisiClass }}"
                              style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:500;">
                            {{ ucfirst($dp->kondisi_buku) }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        @if($dp->status_penggantian === 'tidak_perlu')
                            <span style="color:rgba(255,255,255,0.30);font-size:12px;">—</span>
                        @elseif($dp->status_penggantian === 'sudah_diganti')
                            <span style="background:rgba(52,211,153,0.15);color:#6ee7b7;border:1px solid rgba(52,211,153,0.25);border-radius:20px;padding:3px 10px;font-size:12px;">
                                ✓ Sudah Diganti
                            </span>
                        @else
                            @if(auth()->user()->isAdmin())
                            <button type="button"
                            onclick="showKonfirmasiGanti({{ $dp->id }}, '{{ addslashes($dp->detailPinjam->buku->judul) }}')"
                            style="background:rgba(251,191,36,0.15);color:#fbbf24;border:1px solid rgba(251,191,36,0.25);border-radius:20px;padding:3px 12px;font-size:12px;cursor:pointer;">
                            ⏳ Belum Diganti
                            </button>
                            <form id="form-ganti-{{ $dp->id }}" action="{{ route('pengembalian.penggantian', $dp) }}" method="POST" style="display:none;">
                            @csrf @method('PUT')
                        <input type="hidden" name="status_penggantian" value="sudah_diganti">
                    </form>
                            @else
                            <span style="background:rgba(251,191,36,0.15);color:#fbbf24;border:1px solid rgba(251,191,36,0.25);border-radius:20px;padding:3px 10px;font-size:12px;">
                                ⏳ Belum Diganti
                            </span>
                            @endif
                        @endif
                    </td>
                    <td>{{ $dp->tgl_kembali_aktual->format('d/m/Y') }}</td>
                    <td style="text-align:right;font-weight:600;{{ $dp->denda > 0 ? 'color:#fca5a5;' : 'color:rgba(255,255,255,0.40);' }}">
                        Rp {{ number_format($dp->denda, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:rgba(255,255,255,0.04);">
                    <td colspan="4"
                        style="padding:12px 16px;text-align:right;color:rgba(255,255,255,0.60);font-size:13px;font-weight:600;border-top:1px solid rgba(255,255,255,0.07);">
                        Total Denda
                    </td>
                    <td style="padding:12px 16px;text-align:right;font-weight:700;font-size:15px;color:#fca5a5;border-top:1px solid rgba(255,255,255,0.07);">
                        Rp {{ number_format($pengembalian->total_denda, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Actions --}}
    <div style="display:flex;gap:10px;">
        <a href="{{ route('pengembalian.index') }}" class="btn-secondary"
           style="padding:10px 20px;border-radius:10px;font-size:13.5px;text-decoration:none;display:inline-block;">
            ← Kembali
        </a>
        <a href="{{ route('pinjam.show', $pengembalian->pinjam) }}"
           style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:10px;font-size:13.5px;text-decoration:none;background:rgba(99,102,241,0.15);border:1px solid rgba(99,102,241,0.25);color:#a5b4fc;">
            Lihat Transaksi Pinjam
        </a>
    </div>
</div>
{{-- Modal Konfirmasi Penggantian --}}
<div id="modalGanti" onclick="closeModalGanti()"
     style="display:none;position:fixed;inset:0;z-index:50;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
    <div onclick="event.stopPropagation()"
         style="background:#1e2a45;border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:28px;width:100%;max-width:380px;margin:16px;text-align:center;">

        <div style="width:52px;height:52px;background:rgba(251,191,36,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <svg style="width:26px;height:26px;color:#fbbf24;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>

        <h3 style="color:white;font-size:16px;font-weight:600;margin-bottom:8px;">Tandai Buku Sudah Diganti?</h3>
        <p id="modalGantiJudul" style="color:#fbbf24;font-size:14px;font-weight:500;margin-bottom:8px;"></p>
        <p style="color:rgba(255,255,255,0.35);font-size:12px;margin-bottom:24px;">Stok buku akan otomatis bertambah setelah ditandai sudah diganti.</p>

        <div style="display:flex;gap:10px;justify-content:center;">
            <button onclick="closeModalGanti()" class="btn-secondary"
                    style="padding:9px 22px;border-radius:10px;font-size:13px;cursor:pointer;">
                Batal
            </button>
            <button id="btnKonfirmasiGanti" onclick="submitGanti()"
                    style="background:linear-gradient(135deg,#f59e0b,#fbbf24);color:white;border:none;padding:9px 22px;border-radius:10px;font-size:13px;font-weight:500;cursor:pointer;">
                Ya, Sudah Diganti
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentGantiId = null;

function showKonfirmasiGanti(id, judul) {
    currentGantiId = id;
    document.getElementById('modalGantiJudul').textContent = judul;
    document.getElementById('modalGanti').style.display = 'flex';
}

function closeModalGanti() {
    document.getElementById('modalGanti').style.display = 'none';
    currentGantiId = null;
}

function submitGanti() {
    if (currentGantiId) {
        document.getElementById('form-ganti-' + currentGantiId).submit();
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModalGanti();
});
</script>
@endpush
@endsection