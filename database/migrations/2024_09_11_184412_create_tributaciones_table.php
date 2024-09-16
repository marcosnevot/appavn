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
        Schema::create('tributaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // Nombre de la tributación
            $table->timestamps();
        });

        // Insertar opciones iniciales
        DB::table('tributaciones')->insert([
            ['nombre' => 'E.D.S'],
            ['nombre' => 'E.O.'],
            ['nombre' => 'CONTABILIDAD'],
            ['nombre' => 'ALQUILER'],
            ['nombre' => 'RESUMEN ANUAL'],
            ['nombre' => 'IVA SIMPLIFICADO'],
            ['nombre' => 'REGIMEN SIMPLIFICADO'],
            ['nombre' => 'CONTABILIDAD/CLIENTE'],
            ['nombre' => 'ATRIBUCIÓN RENTAS'],
            ['nombre' => 'SIN CLASIFICAR'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tributaciones');
    }
};
