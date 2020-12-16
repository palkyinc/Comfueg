<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePanelHasBarrios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panel_has_barrios', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedSmallInteger('panel_id');
            $table->foreign('panel_id')->references('id')->on('paneles')->constrained()->onUpdate('cascade')
            ->onDelete('restrict');
            $table->unsignedSmallInteger('barrio_id');
            $table->foreign('barrio_id')->references('id')->on('barrios')->constrained()->onUpdate('cascade')
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
        Schema::dropIfExists('panel_has_barrios');
    }
}
