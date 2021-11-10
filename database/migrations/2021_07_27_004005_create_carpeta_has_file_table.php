<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarpetaHasFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carpeta_has_file', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedSmallInteger('id_contract_type');
            $table->string('id_carpeta',10);
            $table->unsignedSmallInteger('id_cliente');
            $table->string('token',255);
            $table->string('referencia',100);
            $table->boolean('activo');
            $table->foreign('id_cliente')->references('id')->on('clientes')->constrained()->onUpdate('cascade')
            ->onDelete('restrict');
            $table->foreign('id_contract_type')->references('id')->on('clientes')->constrained()->onUpdate('cascade')
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
        Schema::dropIfExists('carpeta_has_file');
    }
}
