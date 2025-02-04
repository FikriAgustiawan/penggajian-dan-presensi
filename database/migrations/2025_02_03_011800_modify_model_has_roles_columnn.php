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
        
        // Drop dan recreate table
        Schema::dropIfExists('model_has_roles');
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->string('model_id');  // Ubah tipe menjadi string agar bisa menyimpan NIK
            
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        // Restore data
        foreach($existingData as $data) {
            DB::table('model_has_roles')->insert([
                'role_id' => $data->role_id,
                'model_type' => $data->model_type,
                'model_id' => $data->model_id
            ]);
        }
    }

    public function down()
    {
        // Backup data yang ada
        $existingData = DB::table('model_has_roles')->get();
        
        // Drop dan recreate table dengan struktur lama
        Schema::dropIfExists('model_has_roles');
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        // Restore data
        foreach($existingData as $data) {
            DB::table('model_has_roles')->insert([
                'role_id' => $data->role_id,
                'model_type' => $data->model_type,
                'model_id' => $data->model_id
            ]);
        }
    }
};