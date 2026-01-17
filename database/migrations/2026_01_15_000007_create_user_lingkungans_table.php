<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLingkungansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_lingkungans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('lingkungan_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('lingkungan_id')
                  ->references('id')
                  ->on('master_lingkungans')
                  ->onDelete('cascade');

            // Prevent duplicate assignments
            $table->unique(['user_id', 'lingkungan_id']);

            // Indexes for performance
            $table->index('user_id');
            $table->index('lingkungan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_lingkungans');
    }
}
