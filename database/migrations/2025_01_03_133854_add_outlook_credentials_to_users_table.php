<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOutlookCredentialsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('outlook_password')->nullable()->after('remember_token'); // Contraseña (cifrada)
            $table->timestamp('outlook_credentials_updated_at')->nullable()->after('outlook_password'); // Última actualización
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['outlook_password', 'outlook_credentials_updated_at']);
        });
    }
}
