<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMacAddressExceptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mac_address_exceptions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedSmallInteger('equipo_id');
            $table->foreign('equipo_id')->references('id')->on('equipos')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedSmallInteger('panel_id');
            $table->foreign('panel_id')->references('id')->on('paneles')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->string('description', 30);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mac_address_exceptions');
    }
}
