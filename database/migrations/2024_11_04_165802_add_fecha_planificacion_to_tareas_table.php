<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFechaPlanificacionToTareasTable extends Migration
{
    public function up()
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->date('fecha_planificacion')->nullable()->after('fecha_imputacion'); // Añade la columna después de `fecha_imputacion`
        });
    }

    public function down()
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropColumn('fecha_planificacion'); // Elimina la columna al revertir la migración
        });
    }
}
