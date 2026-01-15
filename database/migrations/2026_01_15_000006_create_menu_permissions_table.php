<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();

            $table->foreign('menu_id')
                  ->references('id')
                  ->on('menus')
                  ->onDelete('cascade');

            $table->foreign('permission_id')
                  ->references('id')
                  ->on('permissions')
                  ->onDelete('cascade');

            $table->unique(['menu_id', 'permission_id']);
            $table->index('menu_id');
            $table->index('permission_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_permissions');
    }
}
