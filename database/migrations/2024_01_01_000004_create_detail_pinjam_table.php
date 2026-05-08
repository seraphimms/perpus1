<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pinjam', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjam_id')->constrained('pinjam')->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('buku')->onDelete('restrict');
            $table->unsignedInteger('jumlah')->default(1);
            $table->date('tgl_kembali_estimasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pinjam');
    }
};
