<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRafagasToPlanesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planes', function (Blueprint $table) {
            $table->unsignedSmallInteger('mbt')->nullable();
            $table->unsignedSmallInteger('br')->nullable();
            $table->unsignedSmallInteger('bth')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planes', function (Blueprint $table) {
            $table->dropColumn('mbt');
            $table->dropColumn('br');
            $table->dropColumn('bth');
        });
    }
}
