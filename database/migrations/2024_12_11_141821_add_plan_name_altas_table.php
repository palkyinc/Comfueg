<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Alta;
use App\Models\Plan;

class AddPlanNameAltasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('altas', function (Blueprint $table) {
            $table->unsignedSmallInteger('contrato_id')->nullable();
            $table->foreign('contrato_id')->references('id')->on('contratos')->constrained()->onUpdate('cascade')
            ->onDelete('restrict');
            $table->string('plan_name', 20)->nullable();
        });
        $altas = Alta::get();
        foreach ($altas as $key => $alta) {
            $alta->plan_name =  $alta->relPlan->nombre;
            $alta->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('altas', function (Blueprint $table) {
            $table->dropColumn('contrato_id');
            $table->dropColumn('plan_name');
        });
    }
}
