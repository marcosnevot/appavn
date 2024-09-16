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
        Schema::create('situaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // Nombre de la situación
            $table->timestamps();
        });

        // Insertar opciones iniciales
        DB::table('situaciones')->insert([
            ['nombre' => 'ALTA'],
            ['nombre' => 'BAJA'],
            ['nombre' => 'SIN ACTIVIDAD'],
            ['nombre' => 'OCASIONAL'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('situaciones');
    }
};
