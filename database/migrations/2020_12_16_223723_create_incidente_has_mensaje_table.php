<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncidenteHasMensajeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidente_has_mensajes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            //incidente_id      FK site_has_incidente->id
            $table->unsignedBigInteger('incidente_id');
            $table->foreign('incidente_id')->references('id')->on('site_has_incidentes')->constrained()->onUpdate('cascade')
            ->onDelete('restrict');

            //mensaje           string(255)
            $table->string('mensaje', 255);

            //user_creator      FK user->id
            $table->unsignedBigInteger('user_creator');
            $table->foreign('user_creator')->references('id')->on('users')->constrained()
            ->onUpdate('cascade')
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
        Schema::dropIfExists('incidente_has_mensaje');
    }
}
