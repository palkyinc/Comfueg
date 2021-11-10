<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('titulo_id');
            $table->text('descripcion', 500);
            $table->unsignedBigInteger('asignado_id');
            $table->unsignedBigInteger('creator_id');
            $table->unsignedSmallInteger('cliente_id');
            $table->unsignedSmallInteger('contrato_id')->nullable();
            $table->json('viewers')->nullable();
            $table->boolean('closed');
            $table->foreign('titulo_id')->references('id')->on('issue_titles')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('asignado_id')->references('id')->on('users')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('creator_id')->references('id')->on('users')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('cliente_id')->references('id')->on('clientes')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('contrato_id')->references('id')->on('contratos')->constrained()->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issues');
    }
}
