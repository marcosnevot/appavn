<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEstadoEnumInTareasTable extends Migration
{
    public function up()
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->enum('estado', ['PENDIENTE', 'ENESPERA', 'COMPLETADA'])->nullable()->default('PENDIENTE')->change();
        });        
        
    }

    public function down()
    {
        Schema::table('tareas', function (Blueprint $table) {
            // Revertir los cambios al ENUM original
            $table->enum('estado', ['PENDIENTE', 'ENPROGRESO', 'COMPLETADA'])->nullable()->change();
        });
    }
}
