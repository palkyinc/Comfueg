<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAltasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('altas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedSmallInteger('cliente_id');
            $table->unsignedSmallInteger('direccion_id');
            $table->unsignedSmallInteger('plan_id');
            $table->boolean('programado');
            $table->boolean('instalado');
            $table->boolean('anulado');
            $table->dateTime('instalacion_fecha');
            $table->text('comentarios', 500)->nullable();
            $table->foreign('cliente_id')->references('id')->on('clientes')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('direccion_id')->references('id')->on('direcciones')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('plan_id')->references('id')->on('planes')->constrained()->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('altas');
    }
}
