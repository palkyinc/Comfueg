<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatorToContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->unsignedBigInteger('creator')->nullable();
            $table->unsignedBigInteger('instalator')->nullable();
            $table->foreign('creator')->references('id')->on('users')->constrained()
            ->onUpdate('cascade')
            ->onDelete('restrict');
            $table->foreign('instalator')->references('id')->on('users')->constrained()
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
        Schema::table('contratos', function (Blueprint $table) {
            $table->dropColumn('creator');
            $table->dropColumn('instalator');
        });
    }
}
