<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanTable extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel.
     *
     * @return void
     */
    public function up()
{
    if (!Schema::hasTable('karyawan')) {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->char('nik', 5)->primary();
            $table->string('nama_lengkap', 100);
            $table->string('jabatan', 20);
            $table->string('no_hp', 13);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
}

    /**
     * Undo migration untuk menghapus tabel.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan');
    }
}
