<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePruebasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pruebas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->ipAddress('ip_equipo');
            $table->string('nom_equipo', 45)->nullable();
            $table->macAddress('mac_address')->nullable();
            $table->string('firmware', 45)->nullable();
            $table->string('dispositivo', 45)->nullable();
            $table->smallInteger('clientes_conec')->nullable()->unsigned();
            $table->string('ssid', 15)->nullable();
            $table->smallInteger('canal')->nullable()->unsigned();
            $table->smallInteger('senial')->nullable()->unsigned();
            $table->smallInteger('ruido')->nullable()->unsigned();
            $table->smallInteger('ccq')->nullable()->unsigned();
            $table->smallInteger('uso_cpu')->nullable()->unsigned();
            $table->smallInteger('mem_libre')->nullable()->unsigned();
            $table->smallInteger('tx')->nullable()->unsigned();
            $table->smallInteger('rx')->nullable()->unsigned();
            $table->boolean('lan_conectado')->nullable();
            $table->string('lan_velocidad', 15)->nullable();
            $table->smallInteger('wispro_lost')->nullable()->unsigned();
            $table->smallInteger('wispro_avg')->nullable()->unsigned();
            $table->smallInteger('internet_lost')->nullable()->unsigned();
            $table->smallInteger('internet_avg')->nullable()->unsigned();
            $table->string('panel', 15)->nullable()->nullable();
            $table->smallInteger('horizontal')->nullable()->unsigned();
            $table->smallInteger('vertical')->nullable()->unsigned();
            $table->ipAddress('ip_lan')->nullable();
            $table->ipAddress('ip_wan')->nullable();
            $table->boolean('contactado')->nullable();
            $table->smallInteger('contrato_id')->nullable()->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->constrained()
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
        Schema::dropIfExists('pruebas');
    }
}
