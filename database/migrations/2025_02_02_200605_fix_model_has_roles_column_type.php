<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixModelHasRolesColumnType extends Migration
{
    public function up()
    {
        Schema::dropIfExists('model_has_roles');
        
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->string('model_type');
            $table->string('model_id');
            $table->unsignedBigInteger('role_id');

            $table->primary(['model_id', 'model_type', 'role_id']);
            
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->index(['model_type', 'model_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('model_has_roles');
    }
}