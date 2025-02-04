<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Ubah query yang mencari nik menjadi model_id
        if (Schema::hasTable('model_has_roles')) {
            $existingData = DB::table('model_has_roles')->get();
            Schema::table('model_has_roles', function (Blueprint $table) {
                // Jika ada kolom nik, rename ke model_id
                if (Schema::hasColumn('model_has_roles', 'nik')) {
                    $table->renameColumn('nik', 'model_id');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('model_has_roles')) {
            Schema::table('model_has_roles', function (Blueprint $table) {
                if (Schema::hasColumn('model_has_roles', 'model_id')) {
                    $table->renameColumn('model_id', 'nik');
                }
            });
        }
    }
};