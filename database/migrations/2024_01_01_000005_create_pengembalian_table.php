<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjam_id')->constrained('pinjam')->onDelete('restrict');
            $table->date('tgl_kembali');
            $table->decimal('total_denda', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('detail_pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengembalian_id')->constrained('pengembalian')->onDelete('cascade');
            $table->foreignId('detail_pinjam_id')->constrained('detail_pinjam')->onDelete('restrict');
            $table->date('tgl_kembali_aktual');
            $table->enum('kondisi_buku', ['baik', 'rusak', 'hilang'])->default('baik');
            $table->decimal('denda', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pengembalian');
        Schema::dropIfExists('pengembalian');
    }
};
