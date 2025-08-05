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
        Schema::table('tabel_kegiatan', function (Blueprint $table) {
            $table->unsignedBigInteger('mahasiswa_id');
            $table->string('hari'); // Contoh: "Kamis"
            $table->date('tanggal'); // Contoh: "2023-01-12"
            $table->string('jam'); // Contoh: "08:09 - 14:13"
            $table->text('kegiatan'); // Contoh: "Melakukan Verifikasi Pajak"
      

            // Foreign key constraint
            $table->foreign('mahasiswa_id')->references('id')->on('tabel_mahasiswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
