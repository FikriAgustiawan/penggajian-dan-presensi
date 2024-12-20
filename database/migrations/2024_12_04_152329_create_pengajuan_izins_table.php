<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanIzinsTable extends Migration // Nama kelas diubah
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_izin', function (Blueprint $table) {
            $table->id(); // Kolom id dengan auto increment
            $table->char('nik', 5); // Kolom nik (terhubung dengan tabel karyawan)
            $table->date('tgl_izin')->nullable(); // Kolom tanggal izin
            $table->char('status', 1)->nullable(); // Kolom status izin
            $table->string('keterangan', 255)->nullable(); // Kolom keterangan izin
            $table->char('status_approved', 1)->nullable(); // Status approval izin
            $table->timestamps(); // Kolom created_at dan updated_at

            // Foreign key: nik terhubung ke tabel karyawan
            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuan_izin');
    }
}
