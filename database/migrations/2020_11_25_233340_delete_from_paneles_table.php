<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteFromPanelesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paneles', function (Blueprint $table) {
            $table->dropColumn('cobertura');
            $table->dropColumn('altura');
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
            $table->string('cobertura',45);
            $table->string('altura', 3);
        });
    }
}
