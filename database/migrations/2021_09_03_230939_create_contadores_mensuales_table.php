<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContadoresMensualesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contadores_mensuales', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedSmallInteger('contrato_id');
            $table->unsignedSmallInteger('anio');
            $table->unsignedTinyInteger('ultimo_mes');
            $table->unsignedBigInteger('ene')->nullable();
            $table->unsignedBigInteger('feb')->nullable();
            $table->unsignedBigInteger('mar')->nullable();
            $table->unsignedBigInteger('abr')->nullable();
            $table->unsignedBigInteger('may')->nullable();
            $table->unsignedBigInteger('jun')->nullable();
            $table->unsignedBigInteger('jul')->nullable();
            $table->unsignedBigInteger('ago')->nullable();
            $table->unsignedBigInteger('sep')->nullable();
            $table->unsignedBigInteger('oct')->nullable();
            $table->unsignedBigInteger('nov')->nullable();
            $table->unsignedBigInteger('dic')->nullable();
            $table->foreign('contrato_id')->references('id')->on('contratos')->constrained()->onUpdate('cascade')
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
        Schema::dropIfExists('contadores_mensuales');
    }
}
