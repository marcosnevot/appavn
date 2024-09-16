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
        Schema::create('tipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // Nombre del tipo de tarea
            $table->timestamps();
        });

        // Insertar opciones iniciales
        DB::table('tipos')->insert([
            ['nombre' => 'FISCAL'],
            ['nombre' => 'CONTABLE'],
            ['nombre' => 'LABORAL'],
            ['nombre' => 'COMUNIDADES'],
            ['nombre' => 'FIRMAS'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipos');
    }
};
