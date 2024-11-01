<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyFacturadoColumnInTareasTable extends Migration
{
    public function up()
    {
        Schema::table('tareas', function (Blueprint $table) {
            // Convertir 'facturado' de VARCHAR a ENUM con las opciones deseadas
            $table->enum('facturado', ['SI', 'NO', 'NUNCA'])->nullable()->default('NO')->change();
        });
    }

    public function down()
    {
        Schema::table('tareas', function (Blueprint $table) {
            // Revertir 'facturado' a VARCHAR en caso de necesitar revertir la migración
            $table->string('facturado', 255)->nullable()->change();
        });
    }
}
