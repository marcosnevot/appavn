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
        Schema::table('tareas', function (Blueprint $table) {
            // Eliminar la clave foránea actual
            $table->dropForeign(['cliente_id']);

            // Añadir la nueva clave foránea con onDelete('set null')
            $table->foreign('cliente_id')
                ->references('id')
                ->on('clientes')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tareas', function (Blueprint $table) {
            // Eliminar la clave foránea actual
            $table->dropForeign(['cliente_id']);

            // Restaurar la clave foránea con onDelete('cascade')
            $table->foreign('cliente_id')
                ->references('id')
                ->on('clientes')
                ->onDelete('cascade');
        });
    }
};
