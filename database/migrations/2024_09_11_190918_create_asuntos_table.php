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
        Schema::create('asuntos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // Nombre del asunto
            $table->timestamps();
        });

        // Insertar opciones iniciales
        DB::table('asuntos')->insert([
            ['nombre' => 'ARCHIVO DOCUMENTACIÓN'],
            ['nombre' => 'ASISTENCIA FIRMAS'],
            ['nombre' => 'CERTIFICADOS ELECTRÓNICOS'],
            ['nombre' => 'CERTIFICADOS MENSUALES'],
            ['nombre' => 'COMUNIDADES'],
            ['nombre' => 'CONTABILIZAR BANCOS'],
            ['nombre' => 'CONTABILIZAR FACTURAS'],
            ['nombre' => 'DESCARGAR MOVIMIENTOS BANCOS Y CONTABILIZAR'],
            ['nombre' => 'DONACIONES'],
            ['nombre' => 'EMITIR FACTURAS'],
            ['nombre' => 'FACTURA ELECTRONICAS'],
            ['nombre' => 'INCIDENCIAS INFORMATICAS'],
            ['nombre' => 'LABORAL'],
            ['nombre' => 'REMESA'],
            ['nombre' => 'RENTAS'],
            ['nombre' => 'RESTO DIA'],
            ['nombre' => 'REVISAR CONTABILIDAD'],
            ['nombre' => 'SUBVENCIONES'],
            ['nombre' => 'SUCESIONES'],
            ['nombre' => 'TAREAS DIVERSAS'],
            ['nombre' => 'TELEFONO'],
            ['nombre' => 'REQUERIMIENTO AEAT'],
            ['nombre' => 'FIRMA'],
            ['nombre' => 'DESCARGAR NOTIFICACIONES'],
            ['nombre' => 'DESCARGAR FACTURAS Y SUBIRLAS A DIFS'],
            ['nombre' => 'IMPUESTO SOCIEDADES Y DEPOSITOS CUENTAS'],
            ['nombre' => 'CITA'],
            ['nombre' => 'LLAMADA TELEFONICA']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asuntos');
    }
};
