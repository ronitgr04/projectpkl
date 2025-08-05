<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateEnumLevelInTabelUser extends Migration
{
    public function up(): void
    {
        // Ubah enum dengan menambahkan value 'User'
        DB::statement("ALTER TABLE tabel_user MODIFY level ENUM('Admin', 'Manager', 'User') DEFAULT NULL");
    }

    public function down(): void
    {
        // Rollback ke enum sebelumnya (tanpa 'User')
        DB::statement("ALTER TABLE tabel_user MODIFY level ENUM('Admin', 'Manager') DEFAULT NULL");
    }
}
