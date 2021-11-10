<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues_updates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('issue_id');
            $table->text('descripcion', 500);
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('issue_id')->references('id')->on('issues')->constrained()->onUpdate('cascade')
            ->onDelete('restrict');
            $table->foreign('usuario_id')->references('id')->on('users')->constrained()->onUpdate('cascade')
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
        Schema::dropIfExists('issues_updates');
    }
}
