<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Backup data yang ada
        $existingData = DB::table('model_has_roles')->get();
        
        // Drop tabel lama
        Schema::dropIfExists('model_has_roles');

        // Buat ulang dengan struktur yang benar
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
                
            $table->primary(['model_id', 'model_type', 'role_id']);
        });

        // Restore data yang sebelumnya di-backup (jika ada)
        foreach($existingData as $data) {
            DB::table('model_has_roles')->insert([
                'role_id' => $data->role_id,
                'model_type' => $data->model_type,
                'model_id' => $data->model_id ?? $data->nik,
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('model_has_roles');
    }
};