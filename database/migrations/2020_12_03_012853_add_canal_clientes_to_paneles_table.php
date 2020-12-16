<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCanalClientesToPanelesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paneles', function (Blueprint $table) {
            $table->string('canal', 4)->after('altura')->nullable();
            $table->string('clientes', 3)->after('altura')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paneles', function (Blueprint $table) {
            $table->dropColumn('canal');
            $table->dropColumn('clientes');
        });
    }
}
