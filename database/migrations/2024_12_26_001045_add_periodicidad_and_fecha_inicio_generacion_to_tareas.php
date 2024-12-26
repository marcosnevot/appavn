<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeriodicidadAndFechaInicioGeneracionToTareas extends Migration
{
    public function up()
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->enum('periodicidad', ['NO', 'SEMANAL', 'MENSUAL', 'TRIMESTRAL', 'ANUAL'])->default('NO');
            $table->date('fecha_inicio_generacion')->nullable()->after('fecha_planificacion');
        });
    }

    public function down()
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropColumn('periodicidad');
            $table->dropColumn('fecha_inicio_generacion');
        });
    }
}
