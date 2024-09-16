<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('tipo_clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // Nombre del tipo de cliente
            $table->timestamps();
        });

        // Insertar opciones iniciales
        DB::table('tipo_clientes')->insert([
            ['nombre' => 'PERSONA FÍSICA'],
            ['nombre' => 'SOCIEDAD'],
            ['nombre' => 'ASOCIACIÓN'],
            ['nombre' => 'SOCIEDAD CIVIL'],
            ['nombre' => 'SOCIEDAD LIMITADA'],
            ['nombre' => 'COMUNIDAD DE BIENES'],
            ['nombre' => 'COOPERATIVA'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_clientes');
    }
};
