<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBtfDebitosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('btf_debitos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedSmallInteger('cliente_id');
            $table->float('importe', 11, 2);
            $table->string('dni', 8);
            $table->string('cuenta', 9);
            $table->string('tipo_cuenta', 2);
            $table->string('sucursal', 2);
            $table->date('fecha_presentacion')->nullable();
            $table->boolean('excepcional');
            $table->boolean('desactivado');
            $table->foreign('cliente_id')->references('id')->on('clientes')->constrained()->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('btf_debitos');
    }
}
