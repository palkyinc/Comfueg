<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteHasIncidentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_has_incidentes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            //tipo              'INCIDENTE', 'DEUDA TECNICA'
            $table->enum('tipo', ['INCIDENTE', 'DEUDA TECNICA']);

            //nombre            timestamp_create
            
            //inicio            date ddmmyy hhmm
            $table->dateTime('inicio');
            
            //final             date ddmmyy hhmm nulleable
            $table->dateTime('final')->nullable();

            //afectado          FK panel->idate
            $table->unsignedSmallInteger('afectado');
            $table->foreign('afectado')->references('id')->on('paneles')->constrained()->onUpdate('cascade')
                ->onDelete('restrict');
            
            //afectados_indi    string (No aplica a paneles)
            $table->string('afectados_indi', 255);
            
            //sitios_afectados  string
            $table->string('sitios_afectados', 255);
            
            //barrios_afectados string
            $table->string('barrios_afectados', 255);
            
            //causa             string(255)
            $table->string('causa', 255);
            
            //diagnostico       string(255)
            $table->string('mensaje_clientes', 255);

            //user_creator      FK User->id
            $table->unsignedBigInteger('user_creator');
            $table->foreign('user_creator')->references('id')->on('users')->constrained()
            ->onUpdate('cascade')
            ->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_has_incidentes');
    }
}
