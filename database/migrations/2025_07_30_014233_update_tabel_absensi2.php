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
        Schema::table('tabel_absensi', function (Blueprint $table) {
            $table->foreignId('mahasiswa_id')->constrained('tabel_mahasiswa')->onDelete('cascade');
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Belum Absensi'])->default('Belum Absensi');
            $table->string('waktu')->nullable();
            $table->string('hari');
            $table->date('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tabel_absensi', function (Blueprint $table) {
            $table->foreignId('mahasiswa_id')->constrained('tabel_mahasiswa')->onDelete('cascade');
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Belum Absensi'])->default('Belum Absensi');
            $table->string('waktu')->nullable();
            $table->string('hari');
            $table->date('tanggal');
        });
    }
};
