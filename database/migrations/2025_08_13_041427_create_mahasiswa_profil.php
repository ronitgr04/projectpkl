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
        Schema::create('tabel_profil_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->string('no_telp', 15)->nullable();
            $table->text('alamat')->nullable();
            $table->string('jurusan')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('nama_pembimbing')->nullable();
            $table->string('no_telp_pembimbing', 15)->nullable();
            $table->string('email_pembimbing')->nullable();
            $table->text('deskripsi_diri')->nullable();
            $table->json('skills')->nullable(); // Untuk menyimpan array skills
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('github')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('mahasiswa_id')->references('id')->on('tabel_mahasiswa')->onDelete('cascade');

            // Index untuk performa
            $table->index('mahasiswa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_profil_mahasiswa');
    }
};