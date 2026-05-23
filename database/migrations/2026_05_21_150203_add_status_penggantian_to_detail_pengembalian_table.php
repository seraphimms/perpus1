<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('detail_pengembalian', function (Blueprint $table) {
        $table->enum('status_penggantian', ['tidak_perlu', 'belum_diganti', 'sudah_diganti'])
              ->default('tidak_perlu')
              ->after('denda');
    });
}

public function down(): void
{
    Schema::table('detail_pengembalian', function (Blueprint $table) {
        $table->dropColumn('status_penggantian');
    });
}
};
