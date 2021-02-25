<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCablePanelessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paneles', function (Blueprint $table) {
            $table->string('cable_tipo', 50)->after('canal')->nullable();
            $table->date('cable_fecha')->after('canal')->nullable();
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
            $table->dropColumn('cable_tipo');
            $table->dropColumn('cable_fecha');
        });
    }
}
