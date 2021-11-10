<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrecedenciaToSiteHasIncidentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_has_incidentes', function (Blueprint $table) {
            $table->unsignedBigInteger('precedencia')->nullable();
            $table->dateTime('fecha_tentativa')->nullable();
            $table->string('prioridad', 10)->nullable();
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
            $table->dropColumn('precedencia'); 
            $table->dropColumn('fecha_tentativa'); 
            $table->dropColumn('prioridad'); 
        });
    }
}
