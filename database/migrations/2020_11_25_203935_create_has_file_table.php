<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHasFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('has_file', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entidad-id');
            $table->enum('entidad', ['panel', 'sitio']);
            $table->enum('tipo', ['cover', 'photo', 'file', 'scheme']);
            $table->string('file_name', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('has_file');
    }
}
