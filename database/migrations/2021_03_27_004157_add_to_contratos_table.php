<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratos', function (Blueprint $table)
        {
            $table->boolean('baja');
            $table->unsignedSmallInteger('router_id');
            $table->foreign('router_id')->references('id')->on('equipos')->constrained()->onUpdate('cascade')
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
        Schema::table('contratos', function (Blueprint $table)
        {
            $table->dropColumn('ip_router');
            $table->dropColumn('baja');
        });
    }
}
