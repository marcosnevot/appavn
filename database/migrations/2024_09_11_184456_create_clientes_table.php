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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_fiscal'); // Obligatorio
            $table->string('nif')->nullable();
            $table->string('movil')->nullable();
            $table->string('fijo')->nullable();
            $table->string('email')->nullable();
            $table->string('direccion')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->string('poblacion')->nullable();
            $table->text('datos_bancarios')->nullable();
            
            // Relación con el trabajador responsable (users)
            $table->foreignId('responsable_id')->nullable()->constrained('users')->onDelete('set null');

            // Relación con las tablas dinámicas
            $table->foreignId('tipo_cliente_id')->nullable()->constrained('tipo_clientes')->onDelete('set null');
            $table->foreignId('tributacion_id')->nullable()->constrained('tributaciones')->onDelete('set null');
            $table->foreignId('clasificacion_id')->nullable()->constrained('clasificaciones')->onDelete('set null');
            $table->foreignId('situacion_id')->nullable()->constrained('situaciones')->onDelete('set null');

            $table->string('subclase')->nullable();
            $table->integer('puntaje')->nullable();
            $table->unsignedBigInteger('codigo_sage')->nullable();

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
        Schema::dropIfExists('clientes');
    }
};
