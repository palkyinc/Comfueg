<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSiteHasIncidentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_has_incidentes', function (Blueprint $table) {
            $table->string('afectados_indi', 255)->nullable()->change();
            $table->string('sitios_afectados', 255)->nullable()->change();
            $table->string('barrios_afectados', 255)->nullable()->change();
            $table->string('mensaje_clientes', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_has_incidentes', function (Blueprint $table) {
            $table->string('afectados_indi', 255)->change();
            $table->string('sitios_afectados', 255)->change();
            $table->string('barrios_afectados', 255)->change();
            $table->string('mensaje_clientes', 255)->change();
        });
    }
}
