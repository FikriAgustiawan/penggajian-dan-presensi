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
        Schema::table('presensi', function (Blueprint $table) {
            $table->time('jam_terlambat')->default('00:00');  // Kolom jam keterlambatan
            $table->decimal('denda', 10, 2)->default(0);      // Kolom denda keterlambatan
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('presensi', function (Blueprint $table) {
            $table->dropColumn('jam_terlambat');
            $table->dropColumn('denda');
        });
    }
};
