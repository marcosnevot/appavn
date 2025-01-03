<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAtributosAdicionalesToTareasAndClientes extends Migration
{
    public function up()
    {
        // Agregar el campo JSON a la tabla 'tareas'
        Schema::table('tareas', function (Blueprint $table) {
            $table->json('atributos_adicionales')->nullable()->after('updated_at');
        });

        // Agregar el campo JSON a la tabla 'clientes'
        Schema::table('clientes', function (Blueprint $table) {
            $table->json('atributos_adicionales')->nullable()->after('updated_at');
        });
    }

    public function down()
    {
        // Eliminar el campo JSON de la tabla 'tareas'
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropColumn('atributos_adicionales');
        });

        // Eliminar el campo JSON de la tabla 'clientes'
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('atributos_adicionales');
        });
    }
}
