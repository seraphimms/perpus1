@extends('layouts.app')
@section('title', 'Catat Pengembalian')

@section('content')
<div style="max-width:760px;display:flex;flex-direction:column;gap:16px;">

    {{-- Pilih Transaksi --}}
    @if(!$selectedPinjam)
    <div class="glass" style="border-radius:16px;padding:24px;">
        <p style="color:rgba(255,255,255,0.40);font-size:11px;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;margin-bottom:14px;">Pilih Transaksi Peminjaman</p>
        <form action="{{ route('pengembalian.create') }}" method="GET" style="display:flex;gap:10px;">
            <select name="pinjam_id" class="glass-select"
                    style="flex:1;border-radius:10px;padding:10px 14px;font-size:13px;box-sizing:border-box;">
                <option value="">-- Pilih Transaksi --</option>
                @foreach($pinjamAktif as $p)
                <option value="{{ $p->id }}">
                    #{{ str_pad($p->id,5,'0',STR_PAD_LEFT) }} — {{ $p->user->nama }}
                    (pinjam: {{ $p->tgl_pinjam->format('d/m/Y') }})
                </option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary"
                    style="padding:10px 20px;border-radius:10px;font-size:13.5px;border:none;cursor:pointer;white-space:nowrap;">
                Pilih
            </button>
        </form>
        @if($pinjamAktif->isEmpty())
        <div style="margin-top:16px;padding:14px;background:rgba(251,191,36,0.07);border:1px solid rgba(251,191,36,0.20);border-radius:10px;">
            <p style="color:#fde68a;font-size:13px;">Tidak ada transaksi peminjaman aktif saat ini.</p>
        </div>
        @endif
    </div>

    @else
    {{-- Info Transaksi Terpilih --}}
    <div class="glass" style="border-radius:16px;padding:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div style="display:flex;align-items:center;gap:14px;">
            <div style="width:40px;height:40px;background:linear-gradient(135deg,#10b981,#34d399);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:white;flex-shrink:0;">
                {{ strtoupper(substr($selectedPinjam->user->nama,0,1)) }}
            </div>
            <div>
                <p style="color:white;font-weight:600;font-size:15px;">{{ $selectedPinjam->user->nama }}</p>
                <p style="color:rgba(255,255,255,0.45);font-size:12.5px;">
                    Transaksi #{{ str_pad($selectedPinjam->id,5,'0',STR_PAD_LEFT) }}
                    &bull; Pinjam: {{ $selectedPinjam->tgl_pinjam->format('d/m/Y') }}
                </p>
            </div>
        </div>
        <a href="{{ route('pengembalian.create') }}"
           style="font-size:13px;color:#93c5fd;text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
            Ganti Transaksi
        </a>
    </div>

    {{-- Form Pengembalian --}}
    <div class="glass" style="border-radius:16px;padding:24px;">
        <form action="{{ route('pengembalian.store') }}" method="POST">
            @csrf
            <input type="hidden" name="pinjam_id" value="{{ $selectedPinjam->id }}">

            {{-- Tanggal Kembali --}}
            <div style="margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid rgba(255,255,255,0.08);">
                <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">
                    Tanggal Kembali <span style="color:#f87171;">*</span>
                </label>
                <input type="date" name="tgl_kembali" value="{{ old('tgl_kembali', date('Y-m-d')) }}"
                       class="glass-input" style="border-radius:10px;padding:10px 14px;font-size:13px;width:200px;box-sizing:border-box;">
                @error('tgl_kembali')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
            </div>

            {{-- Detail per Buku --}}
            <p style="color:rgba(255,255,255,0.40);font-size:11px;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;margin-bottom:14px;">Kondisi Buku yang Dikembalikan</p>

            <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:20px;">
                @foreach($selectedPinjam->detailPinjam as $i => $dp)
                <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:16px;">
                    <input type="hidden" name="detail[{{ $i }}][detail_pinjam_id]" value="{{ $dp->id }}">
                    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                        <div style="flex:1;min-width:200px;">
                            <p style="color:white;font-weight:500;font-size:14px;">{{ $dp->buku->judul }}</p>
                            <p style="color:rgba(255,255,255,0.40);font-size:12px;margin-top:3px;">
                                {{ $dp->buku->penulis }} &bull; Jumlah: {{ $dp->jumlah }} eksemplar
                            </p>
                            <div style="margin-top:8px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                <span style="font-size:12px;color:rgba(255,255,255,0.50);">
                                    Est. kembali: <strong style="color:rgba(255,255,255,0.75);">{{ $dp->tgl_kembali_estimasi->format('d/m/Y') }}</strong>
                                </span>
                                @if($dp->tgl_kembali_estimasi->isPast())
                                @php
                                $hariTelat = 0;
                                $current = $dp->tgl_kembali_estimasi->copy()->addDay();
                                while ($current->lte(now()->startOfDay())) {
                                    if ($current->dayOfWeek !== 0 && $current->dayOfWeek !== 6) {
                                        $hariTelat++;
                                    }
                                    $current->addDay();
                                }
                            @endphp
                            <span class="badge-red" style="display:inline-block;padding:2px 9px;border-radius:20px;font-size:11.5px;">
                                Terlambat {{ $hariTelat }} hari
                            </span>
                            @endif
                            </div>
                        </div>
                        <div style="flex-shrink:0;">
                            <label style="display:block;color:rgba(255,255,255,0.55);font-size:12px;font-weight:500;margin-bottom:6px;">Kondisi Buku</label>
                            <select name="detail[{{ $i }}][kondisi_buku]" class="glass-select"
                            style="border-radius:9px;padding:8px 12px;font-size:13px;min-width:190px;box-sizing:border-box;"
                            onchange="updateNotice(this, {{ $i }})">
                            <option value="baik">✓ Baik</option>
                            <option value="rusak">⚠ Rusak — wajib ganti buku</option>
                            <option value="hilang">✕ Hilang — wajib ganti buku</option>
                        </select>

                    {{-- Notifikasi kondisi --}}
                    <div id="notice-{{ $i }}" style="display:none;margin-top:8px;border-radius:8px;padding:8px 12px;font-size:12px;"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

            {{-- Info Denda --}}
            <div style="padding:12px 16px;background:rgba(251,191,36,0.07);border:1px solid rgba(251,191,36,0.18);border-radius:10px;margin-bottom:20px;">
                <p style="color:#fde68a;font-size:13px;line-height:1.6;">
                <strong>Ketentuan denda:</strong> Keterlambatan Rp 1.000/hari kerja/eksemplar &bull;
                Rusak & Hilang: wajib mengganti buku dengan judul yang sama, tidak ada denda uang &bull;
                Rusak Dan Hilang: stok tidak dikembalikan
            </p>
            </div>

            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn-success"
                        style="padding:10px 22px;border-radius:10px;font-size:13.5px;border:none;cursor:pointer;">
                    Simpan Pengembalian
                </button>
                <a href="{{ route('pengembalian.index') }}" class="btn-secondary"
                   style="padding:10px 22px;border-radius:10px;font-size:13.5px;text-decoration:none;display:inline-block;">
                    Batal
                </a>
            </div>
        </form>
    </div>
    @endif
</div>
@push('scripts')
<script>
function updateNotice(select, index) {
    const notice = document.getElementById('notice-' + index);
    const kondisi = select.value;
    const card = select.closest('div[style*="background"]');
    const judulEl = card ? card.querySelector('p') : null;
    const judul = judulEl ? judulEl.textContent.trim() : 'buku ini';

    if (kondisi === 'rusak') {
        notice.style.display = 'block';
        notice.style.background = 'rgba(245,158,11,0.12)';
        notice.style.border = '1px solid rgba(245,158,11,0.25)';
        notice.style.color = '#fde68a';
        notice.innerHTML = '⚠ Member wajib mengganti <strong>' + judul + '</strong> dengan judul yang sama. Tidak ada denda uang.';
    } else if (kondisi === 'hilang') {
        notice.style.display = 'block';
        notice.style.background = 'rgba(239,68,68,0.12)';
        notice.style.border = '1px solid rgba(239,68,68,0.25)';
        notice.style.color = '#fca5a5';
        notice.innerHTML = '✕ Member wajib mengganti <strong>' + judul + '</strong> dengan judul yang sama. Tidak ada denda uang.';
    } else {
        notice.style.display = 'none';
    }
}
</script>
@endpush
@endsection
