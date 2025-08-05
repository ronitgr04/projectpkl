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
        Schema::table('tabel_mahasiswa', function (Blueprint $table) {
            $table->string('nama');
            $table->string('universitas');
            $table->string('nim')->unique();
            $table->date('mulai_magang');
            $table->date('akhir_magang');
            $table->string('foto')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tabel_mahasiswa', function (Blueprint $table) {
            $table->string('nama');
            $table->string('universitas');
            $table->string('nim')->unique();
            $table->date('mulai_magang');
            $table->date('akhir_magang');
            $table->string('foto')->nullable(); 
        });
    }
};
