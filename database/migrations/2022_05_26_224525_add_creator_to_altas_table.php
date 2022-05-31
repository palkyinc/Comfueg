<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatorToAltasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('altas', function (Blueprint $table) {
            $table->unsignedBigInteger('creator')->nullable();
            $table->foreign('creator')->references('id')->on('users')->constrained()
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
        Schema::table('altas', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
    }
}
