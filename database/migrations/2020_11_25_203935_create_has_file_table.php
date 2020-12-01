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
        Schema::create('entity_has_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entidad_id');
            $table->enum('entidad', ['PANEL', 'SITIO']);
            $table->enum('tipo', ['COVER', 'PHOTO', 'FILE', 'SCHEME']);
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
