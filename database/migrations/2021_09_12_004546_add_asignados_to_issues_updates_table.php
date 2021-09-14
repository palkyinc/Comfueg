<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAsignadosToIssuesUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('issues_updates', function (Blueprint $table) {
            $table->unsignedBigInteger('asignadoAnt_id');
            $table->unsignedBigInteger('asignadoSig_id');
            $table->foreign('asignadoAnt_id')->references('id')->on('users')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('asignadoSig_id')->references('id')->on('users')->constrained()->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('issues_updates', function (Blueprint $table) {
            $table->dropColumn('asignadoAnt_id');
            $table->dropColumn('asignadoSig_id');
        });
    }
}
