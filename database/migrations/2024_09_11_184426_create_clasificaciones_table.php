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
        Schema::create('clasificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // Nombre de la clasificación
            $table->timestamps();
        });

        // Insertar opciones iniciales
        DB::table('clasificaciones')->insert([
            ['nombre' => 'AGRICULTOR'],
            ['nombre' => 'HOSTELERÍA'],
            ['nombre' => 'CONSTRUCCIÓN'],
            ['nombre' => 'SERVICIOS'],
            ['nombre' => 'EMPRESARIO'],
            ['nombre' => 'REGANTES'],
            ['nombre' => 'DEPORTES'],
            ['nombre' => 'CULTURAL'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clasificaciones');
    }
};
