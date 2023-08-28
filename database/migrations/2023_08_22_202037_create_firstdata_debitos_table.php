<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirstdataDebitosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firstdata_debitos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            /*
            Numero de comercio 
            cuotas
            cuotas plan
            */
            $table->unsignedSmallInteger('cliente_id');
            $table->unsignedBigInteger('concepto_id');
            $table->float('importe', 9, 2);
            $table->string('dni', 8);
            $table->string('num_tarjeta', 16);
            $table->date('fecha_presentacion')->nullable();
            $table->boolean('excepcional');
            $table->boolean('desactivado');
            $table->foreign('cliente_id')->references('id')->on('clientes')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('concepto_id')->references('id')->on('conceptos_debitos')->constrained()->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firstdata_debitos');
    }
}
