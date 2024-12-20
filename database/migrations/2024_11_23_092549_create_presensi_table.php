<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    if (!Schema::hasTable('presensi')) {
        Schema::create('presensi', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->char('nik', 5);
            $table->date('tgl_presensi');
            $table->time('jam_in');
            $table->time('jam_out');
            $table->string('foto_in');
            $table->string('foto_out');
            $table->text('location');
            $table->timestamps();
        });
    }
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presensi');
    }
};
