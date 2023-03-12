<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSimpatisanToDataJemaats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_jemaats', function (Blueprint $table) {
            $table->boolean('is_simpatisan')->after('jemaat_kk_status')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_jemaats', function (Blueprint $table) {
            $table->dropColumn('is_simpatisan');
        });
    }
}
