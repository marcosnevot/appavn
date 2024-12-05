<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateClientesAndTareas extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Agregar nuevos campos a la tabla clientes
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('segundo_telefono')->nullable()->after('movil'); // Segundo teléfono
            $table->string('persona_contacto')->nullable()->after('segundo_telefono'); // Persona de contacto
        });

        // Agregar nuevos estados al campo ENUM en la tabla tareas
        Schema::table('tareas', function (Blueprint $table) {
            DB::statement("ALTER TABLE tareas MODIFY COLUMN estado ENUM('PENDIENTE', 'ENESPERA', 'COMPLETADA', 'PLANIFICADA', 'RECURRENTE/TRIMESTRE')");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Revertir los cambios en la tabla clientes
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('segundo_telefono'); // Eliminar el campo segundo teléfono
            $table->dropColumn('persona_contacto'); // Eliminar el campo persona de contacto
        });

        // Revertir los cambios en el campo ENUM de la tabla tareas
        Schema::table('tareas', function (Blueprint $table) {
            DB::statement("ALTER TABLE tareas MODIFY COLUMN estado ENUM('PENDIENTE', 'ENESPERA', 'COMPLETADA')");
        });
    }
}
