<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTablePreferencesTable extends Migration
{
    public function up()
    {
        Schema::create('user_table_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID del usuario
            $table->string('table_name'); // Nombre de la tabla (para soportar múltiples tablas)
            $table->json('visible_columns'); // JSON con las columnas visibles
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_table_preferences');
    }
}
