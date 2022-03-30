<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataKeluargasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_keluargas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_stambuk');
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nama_keluarga')->nullable();
            $table->integer('status_hubungan')->nullable()->unsigned();
            $table->integer('id_ayah')->nullable();
            $table->integer('id_ibu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_keluargas');
    }
}
