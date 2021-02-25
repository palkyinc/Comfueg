<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePruebasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pruebas', function (Blueprint $table) {
            $table->string('canal', 15)->nullable()->change();
            $table->string('senial', 15)->nullable()->change();
            $table->string('ruido', 15)->nullable()->change();
            $table->string('ccq', 15)->nullable()->change();
            $table->string('uso_cpu', 15)->nullable()->change();
            $table->string('mem_libre', 15)->nullable()->change();
            $table->string('tx', 15)->nullable()->change();
            $table->string('rx', 15)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pruebas', function (Blueprint $table) {
            $table->smallInteger('canal')->nullable()->unsigned()->change();
            $table->smallInteger('senial')->nullable()->unsigned()->change();
            $table->smallInteger('ruido')->nullable()->unsigned()->change();
            $table->smallInteger('uso_cpu')->nullable()->unsigned()->change();
            $table->smallInteger('ccq')->nullable()->unsigned()->change();
            $table->smallInteger('mem_libre')->nullable()->unsigned()->change();
            $table->smallInteger('tx')->nullable()->unsigned()->change();
            $table->smallInteger('rx')->nullable()->unsigned()->change();
        });
    }
}
