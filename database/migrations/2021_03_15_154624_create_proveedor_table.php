<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 30);
            $table->boolean('estado');
            $table->string('interface', 4);
            $table->boolean('esVlan');
            $table->unsignedInteger('bajada');
            $table->unsignedInteger('subida');
            $table->unsignedInteger('classifier');
            $table->ipAddress('dns');
            $table->unsignedSmallInteger('gateway_id');
            $table->ipAddress('ipGateway');
            $table->boolean('sinActualizar');
            $table->timestamps();
            $table->foreign('gateway_id')->references('id')->on('paneles')->constrained()->onUpdate('cascade')
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
        Schema::dropIfExists('proveedor');
    }
}
