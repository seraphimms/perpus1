@extends('layouts.app')
@section('title', 'Buat Transaksi Peminjaman')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<style>
    .choices__inner {
        background: rgba(255,255,255,0.06) !important;
        border: 1px solid rgba(255,255,255,0.12) !important;
        border-radius: 10px !important;
        padding: 6px 10px !important;
        min-height: 42px !important;
    }
    .choices__list--dropdown {
        background: #1e2a45 !important;
        border: 1px solid rgba(255,255,255,0.12) !important;
        border-radius: 10px !important;
    }
    .choices__list--dropdown .choices__item--selectable {
        color: rgba(255,255,255,0.80) !important;
        font-size: 13px !important;
        padding: 8px 14px !important;
    }
    .choices__list--dropdown .choices__item--selectable.is-highlighted {
        background: rgba(99,102,241,0.20) !important;
        color: white !important;
    }
    .choices__input {
        background: transparent !important;
        color: white !important;
        font-size: 13px !important;
    }
    .choices__input--cloned {
        color: white !important;
    }
    .choices__placeholder {
        color: rgba(255,255,255,0.35) !important;
        opacity: 1 !important;
    }
    .choices__list--single .choices__item {
        color: rgba(255,255,255,0.85) !important;
        font-size: 13px !important;
    }
    .choices__list--dropdown {
        top: 100% !important;
        bottom: auto !important;
    }
    .choices[data-type*=select-one] .choices__list--dropdown {
        top: 100% !important;
        bottom: auto !important;
    }
</style>
@endpush

@section('content')
<div style="max-width:780px;">
    <div class="glass" style="border-radius:16px;padding:28px;">
        <form action="{{ route('pinjam.store') }}" method="POST" x-data="pinjamForm()">
            @csrf

            {{-- Info Transaksi --}}
            <div style="margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid rgba(255,255,255,0.08);">
                <p style="color:rgba(255,255,255,0.45);font-size:11px;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;margin-bottom:14px;">Informasi Transaksi</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Anggota <span style="color:#f87171;">*</span></label>
                        <select name="user_id" id="selectAnggota"
                                style="width:100%;box-sizing:border-box;">
                            <option value="">-- Pilih Anggota --</option>
                            @foreach($members as $m)
                            <option value="{{ $m->id }}" {{ old('user_id')==$m->id ? 'selected':'' }}>
                                {{ $m->nama }} ({{ $m->email }})
                            </option>
                            @endforeach
                        </select>
                        @error('user_id')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                    </div>
                    @error('user_id')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror

                    <div id="warningPinjamAktif" style="display:none;margin-top:8px;background:rgba(245,158,11,0.12);border:1px solid rgba(245,158,11,0.25);border-radius:8px;padding:10px 12px;align-items:center;gap:8px;">
                        <svg style="width:16px;height:16px;color:#fbbf24;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <p style="color:#fde68a;font-size:12px;">Member ini masih memiliki pinjaman aktif yang belum dikembalikan. Pastikan sudah dikonfirmasi sebelum meminjam lagi.</p>
                    </div>
                    <div>
                        <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:7px;">Tanggal Pinjam <span style="color:#f87171;">*</span></label>
                        <input type="date" name="tgl_pinjam" value="{{ old('tgl_pinjam', date('Y-m-d')) }}"
                               class="glass-input" style="width:100%;border-radius:10px;padding:10px 14px;font-size:13px;box-sizing:border-box;">
                        @error('tgl_pinjam')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Daftar Buku --}}
            <div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                    <p style="color:rgba(255,255,255,0.45);font-size:11px;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;">Daftar Buku yang Dipinjam</p>
                    <button type="button" @click="tambahBuku()"
                            style="display:inline-flex;align-items:center;gap:6px;background:rgba(99,102,241,0.15);border:1px solid rgba(99,102,241,0.30);color:#a5b4fc;font-size:12.5px;font-weight:500;padding:6px 14px;border-radius:8px;cursor:pointer;">
                        <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Buku
                    </button>
                </div>

                @error('buku')<p style="margin-bottom:10px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror

                <div style="display:flex;flex-direction:column;gap:10px;">
                    <template x-for="(item, index) in items" :key="index">
                        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:16px;">
                            <div style="display:flex;align-items:flex-end;gap:12px;">
                                <div style="flex:2;">
                                    <label style="display:block;color:rgba(255,255,255,0.55);font-size:12px;font-weight:500;margin-bottom:6px;">Pilih Buku <span style="color:#f87171;">*</span></label>
                                    <select :name="`buku[${index}][buku_id]`"
                                            :id="`selectBuku${index}`"
                                            class="buku-select"
                                            style="width:100%;box-sizing:border-box;"
                                            x-init="$nextTick(() => initBukuSelect($el))">
                                        <option value="">-- Pilih Buku --</option>
                                        @foreach($buku as $b)
                                        <option value="{{ $b->id }}">{{ $b->judul }} (stok: {{ $b->jumlah }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div style="width:80px;">
                                    <label style="display:block;color:rgba(255,255,255,0.55);font-size:12px;font-weight:500;margin-bottom:6px;">Jumlah</label>
                                    <input type="number" :name="`buku[${index}][jumlah]`" x-model="item.jumlah" min="1"
                                           class="glass-input" style="width:100%;border-radius:9px;padding:8px 10px;font-size:13px;box-sizing:border-box;">
                                </div>
                                <div style="flex:1;">
                                    <label style="display:block;color:rgba(255,255,255,0.55);font-size:12px;font-weight:500;margin-bottom:6px;">Est. Tgl Kembali</label>
                                    <input type="hidden" :name="`buku[${index}][tgl_kembali_estimasi]`" :value="item.tgl_kembali">
                                    <div class="glass-input" style="width:100%;border-radius:9px;padding:8px 10px;font-size:13px;box-sizing:border-box;color:rgba(255,255,255,0.60);">
                                    <span x-text="item.tgl_kembali ? new Date(item.tgl_kembali).toLocaleDateString('id-ID', {day:'2-digit',month:'short',year:'numeric'}) : '-'"></span>
                                    <span style="font-size:11px;color:rgba(255,255,255,0.35);margin-left:6px;">(3 hari)</span>
                                </div>
                            </div>
                                <button type="button" @click="hapusBuku(index)" x-show="items.length > 1"
                                        style="flex-shrink:0;width:32px;height:32px;background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.25);border-radius:8px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#fca5a5;margin-bottom:2px;">
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid rgba(255,255,255,0.08);">
                <button type="submit" class="btn-primary" style="padding:10px 22px;border-radius:10px;font-size:13.5px;border:none;cursor:pointer;">Simpan Transaksi</button>
                <a href="{{ route('pinjam.index') }}" class="btn-secondary" style="padding:10px 22px;border-radius:10px;font-size:13.5px;text-decoration:none;display:inline-block;">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
// Init select anggota
const memberPinjamAktif = {!! json_encode($memberPinjamAktif) !!};

const choicesAnggota = new Choices(document.getElementById('selectAnggota'), {
    searchEnabled: true,
    searchPlaceholderValue: 'Cari nama anggota...',
    noResultsText: 'Anggota tidak ditemukan',
    itemSelectText: '',
    shouldSort: false,
});

document.getElementById('selectAnggota').addEventListener('change', function() {
    const userId = parseInt(this.value);
    const warning = document.getElementById('warningPinjamAktif');
    if (memberPinjamAktif.includes(userId)) {
        warning.style.display = 'flex';
    } else {
        warning.style.display = 'none';
    }
});

// Fungsi init select buku (dipanggil tiap kali baris baru ditambah)
function initBukuSelect(el) {
    new Choices(el, {
        searchEnabled: true,
        searchPlaceholderValue: 'Cari judul buku...',
        noResultsText: 'Buku tidak ditemukan',
        itemSelectText: '',
        shouldSort: false,
    });
}

function pinjamForm() {
    return {
        items: [{ jumlah: 1, tgl_kembali: '' }],
        tglPinjam: '{{ date('Y-m-d') }}',
        tambahBuku() {
            this.items.push({ jumlah: 1, tgl_kembali: this.hitungTglKembali() });
        },
        hapusBuku(index) {
            this.items.splice(index, 1);
        },
        hitungTglKembali() {
    const d = new Date(this.tglPinjam);
    let count = 0;
    while (count < 3) {
        d.setDate(d.getDate() + 1);
        const day = d.getDay();
        if (day !== 0 && day !== 6) {
            count++;
        }
    }
    return d.toISOString().split('T')[0];
},
        init() {
            this.tglPinjam = document.querySelector('[name=tgl_pinjam]').value;
            this.items[0].tgl_kembali = this.hitungTglKembali();
            document.querySelector('[name=tgl_pinjam]').addEventListener('change', (e) => {
                this.tglPinjam = e.target.value;
                this.items.forEach(item => item.tgl_kembali = this.hitungTglKembali());
            });
        }
    }
}
</script>
@endpush
@endsection