<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\DetailPengembalian;
use App\Models\DetailPinjam;
use App\Models\Kategori;
use App\Models\Pengembalian;
use App\Models\Pinjam;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'nama' => 'Administrator',
            'alamat' => 'Jl. Ahmad Dahlan No. 1, Yogyakarta',
            'telepon' => '081234567890',
            'email' => 'admin@perpustakaan.id',
            'jenis' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        // Members
        $memberData = [
            ['nama' => 'Budi Santoso', 'alamat' => 'Jl. Mawar No. 5', 'telepon' => '082111222333', 'email' => 'budi@mail.com'],
            ['nama' => 'Siti Rahayu', 'alamat' => 'Jl. Melati No. 10', 'telepon' => '083222333444', 'email' => 'siti@mail.com'],
            ['nama' => 'Ahmad Fauzi', 'alamat' => 'Jl. Kenanga No. 3', 'telepon' => '084333444555', 'email' => 'ahmad@mail.com'],
            ['nama' => 'Dewi Lestari', 'alamat' => 'Jl. Anggrek No. 7', 'telepon' => '085444555666', 'email' => 'dewi@mail.com'],
            ['nama' => 'Rizki Pratama', 'alamat' => 'Jl. Cempaka No. 2', 'telepon' => '086555666777', 'email' => 'rizki@mail.com'],
        ];

        foreach ($memberData as $m) {
            User::create(array_merge($m, ['jenis' => 'member', 'password' => Hash::make('member123')]));
        }

        // Kategori
        $kategoriData = [
            ['nama' => 'Fiksi', 'deskripsi' => 'Buku-buku fiksi dan novel'],
            ['nama' => 'Non-Fiksi', 'deskripsi' => 'Buku non-fiksi dan referensi'],
            ['nama' => 'Sains & Teknologi', 'deskripsi' => 'Buku ilmu pengetahuan alam dan teknologi'],
            ['nama' => 'Sejarah', 'deskripsi' => 'Buku sejarah dan biografi'],
            ['nama' => 'Agama', 'deskripsi' => 'Buku-buku keagamaan'],
            ['nama' => 'Pelajaran', 'deskripsi' => 'Buku pelajaran sekolah'],
        ];

        foreach ($kategoriData as $k) {
            Kategori::create($k);
        }

        // Buku
        $bukuData = [
            ['judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'penerbit' => 'Bentang Pustaka', 'tahun' => 2005, 'isbn' => '978-602-8811-45-6', 'jumlah' => 5, 'kategori_id' => 1],
            ['judul' => 'Bumi Manusia', 'penulis' => 'Pramoedya Ananta Toer', 'penerbit' => 'Hasta Mitra', 'tahun' => 1980, 'isbn' => '978-979-444-348-0', 'jumlah' => 3, 'kategori_id' => 1],
            ['judul' => 'Sang Pemimpi', 'penulis' => 'Andrea Hirata', 'penerbit' => 'Bentang Pustaka', 'tahun' => 2006, 'isbn' => '978-602-8811-46-3', 'jumlah' => 4, 'kategori_id' => 1],
            ['judul' => 'Sapiens: Riwayat Singkat Umat Manusia', 'penulis' => 'Yuval Noah Harari', 'penerbit' => 'KPG', 'tahun' => 2017, 'isbn' => '978-602-424-498-0', 'jumlah' => 2, 'kategori_id' => 2],
            ['judul' => 'Atomic Habits', 'penulis' => 'James Clear', 'penerbit' => 'Gramedia Pustaka Utama', 'tahun' => 2019, 'isbn' => '978-602-06-3856-0', 'jumlah' => 3, 'kategori_id' => 2],
            ['judul' => 'Fisika Dasar Jilid 1', 'penulis' => 'Halliday, Resnick, Walker', 'penerbit' => 'Erlangga', 'tahun' => 2010, 'isbn' => '978-979-099-476-4', 'jumlah' => 6, 'kategori_id' => 3],
            ['judul' => 'Kimia Organik', 'penulis' => 'R.T. Morrison', 'penerbit' => 'ITB', 'tahun' => 2012, 'isbn' => null, 'jumlah' => 4, 'kategori_id' => 3],
            ['judul' => 'Sejarah Indonesia Kuno', 'penulis' => 'Slamet Muljana', 'penerbit' => 'LKiS', 'tahun' => 2006, 'isbn' => '978-979-854-065-0', 'jumlah' => 3, 'kategori_id' => 4],
            ['judul' => 'Riyadhus Shalihin', 'penulis' => 'Imam An-Nawawi', 'penerbit' => 'Darul Falah', 'tahun' => 2015, 'isbn' => null, 'jumlah' => 5, 'kategori_id' => 5],
            ['judul' => 'Matematika SMP Kelas 8', 'penulis' => 'Sukino', 'penerbit' => 'Erlangga', 'tahun' => 2020, 'isbn' => '978-602-298-987-4', 'jumlah' => 10, 'kategori_id' => 6],
            ['judul' => 'Bahasa Indonesia SMP Kelas 9', 'penulis' => 'Kementerian Pendidikan', 'penerbit' => 'Kemendikbud', 'tahun' => 2021, 'isbn' => null, 'jumlah' => 8, 'kategori_id' => 6],
            ['judul' => 'IPA Terpadu SMP Kelas 7', 'penulis' => 'Tim Guru Eduka', 'penerbit' => 'Bumi Aksara', 'tahun' => 2022, 'isbn' => '978-602-217-872-1', 'jumlah' => 7, 'kategori_id' => 6],
        ];

        foreach ($bukuData as $b) {
            Buku::create($b);
        }

        $members = User::where('jenis', 'member')->get();
        $allBuku = Buku::all();

        // Transaksi 1: selesai tanpa denda
        $pinjam1 = Pinjam::create([
            'user_id' => $members[0]->id,
            'tgl_pinjam' => now()->subDays(30),
            'status' => 'kembali',
        ]);
        $dp1 = DetailPinjam::create([
            'pinjam_id' => $pinjam1->id,
            'buku_id' => $allBuku[0]->id,
            'jumlah' => 1,
            'tgl_kembali_estimasi' => now()->subDays(23),
        ]);
        $pg1 = Pengembalian::create([
            'pinjam_id' => $pinjam1->id,
            'tgl_kembali' => now()->subDays(24),
            'total_denda' => 0,
        ]);
        DetailPengembalian::create([
            'pengembalian_id' => $pg1->id,
            'detail_pinjam_id' => $dp1->id,
            'tgl_kembali_aktual' => now()->subDays(24),
            'kondisi_buku' => 'baik',
            'denda' => 0,
        ]);

        // Transaksi 2: selesai dengan denda terlambat 5 hari
        $pinjam2 = Pinjam::create([
            'user_id' => $members[1]->id,
            'tgl_pinjam' => now()->subDays(20),
            'status' => 'kembali',
        ]);
        $dp2 = DetailPinjam::create([
            'pinjam_id' => $pinjam2->id,
            'buku_id' => $allBuku[3]->id,
            'jumlah' => 1,
            'tgl_kembali_estimasi' => now()->subDays(13),
        ]);
        $denda2 = 5 * 1000;
        $pg2 = Pengembalian::create([
            'pinjam_id' => $pinjam2->id,
            'tgl_kembali' => now()->subDays(8),
            'total_denda' => $denda2,
        ]);
        DetailPengembalian::create([
            'pengembalian_id' => $pg2->id,
            'detail_pinjam_id' => $dp2->id,
            'tgl_kembali_aktual' => now()->subDays(8),
            'kondisi_buku' => 'baik',
            'denda' => $denda2,
        ]);

        // Transaksi 3: masih dipinjam, 1 buku
        $pinjam3 = Pinjam::create([
            'user_id' => $members[2]->id,
            'tgl_pinjam' => now()->subDays(5),
            'status' => 'pinjam',
        ]);
        $allBuku[6]->decrement('jumlah', 1);
        DetailPinjam::create([
            'pinjam_id' => $pinjam3->id,
            'buku_id' => $allBuku[6]->id,
            'jumlah' => 1,
            'tgl_kembali_estimasi' => now()->addDays(9),
        ]);

        // Transaksi 4: masih dipinjam, 2 buku
        $pinjam4 = Pinjam::create([
            'user_id' => $members[3]->id,
            'tgl_pinjam' => now()->subDays(3),
            'status' => 'pinjam',
        ]);
        $allBuku[9]->decrement('jumlah', 1);
        $allBuku[10]->decrement('jumlah', 1);
        DetailPinjam::create([
            'pinjam_id' => $pinjam4->id,
            'buku_id' => $allBuku[9]->id,
            'jumlah' => 1,
            'tgl_kembali_estimasi' => now()->addDays(11),
        ]);
        DetailPinjam::create([
            'pinjam_id' => $pinjam4->id,
            'buku_id' => $allBuku[10]->id,
            'jumlah' => 1,
            'tgl_kembali_estimasi' => now()->addDays(11),
        ]);
    }
}
