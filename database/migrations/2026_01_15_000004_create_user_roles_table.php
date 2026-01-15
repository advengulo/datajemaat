<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('id_lingkungan')->nullable()->comment('For lingkungan-scoped roles only');
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('role_id')
                  ->references('id')
                  ->on('roles')
                  ->onDelete('cascade');

            $table->foreign('id_lingkungan')
                  ->references('id')
                  ->on('master_lingkungan')
                  ->onDelete('cascade');

            $table->unique(['user_id', 'role_id', 'id_lingkungan'], 'user_role_lingkungan_unique');
            $table->index('user_id');
            $table->index('role_id');
            $table->index('id_lingkungan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
}
