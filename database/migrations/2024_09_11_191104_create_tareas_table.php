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
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            
            // Asunto como un enum dinámico
            $table->foreignId('asunto_id')->nullable()->constrained('asuntos')->onDelete('set null');

            // Tipo como un enum dinámico
            $table->foreignId('tipo_id')->nullable()->constrained('tipos')->onDelete('set null');

            // Subtipo como un enum estático
            $table->enum('subtipo', ['ORDINARIA', 'EXTRAORDINARIA'])->nullable();

            // Estado como un enum estático
            $table->enum('estado', ['PENDIENTE', 'ENPROGRESO', 'COMPLETADA'])->nullable();

            // Relación con cliente
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('cascade'); 

            // Asignación a uno o varios usuarios
            $table->foreignId('asignacion_id')->nullable()->constrained('users')->onDelete('set null');

            // Otros campos
            $table->text('descripcion')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('archivo')->nullable();

            // Facturable como un booleano
            $table->boolean('facturable')->default(false);

            $table->string('facturado')->nullable();
            $table->decimal('precio', 10, 2)->nullable();
            $table->decimal('suplido', 10, 2)->nullable();
            $table->decimal('coste', 10, 2)->nullable();

            // Fechas
            $table->date('fecha_inicio')->nullable(); // Fecha de creación
            $table->date('fecha_vencimiento')->nullable();
            $table->date('fecha_imputacion')->nullable();

            // Tiempo
            $table->decimal('tiempo_previsto', 4, 2)->nullable();
            $table->decimal('tiempo_real', 4, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tareas');
    }
};
