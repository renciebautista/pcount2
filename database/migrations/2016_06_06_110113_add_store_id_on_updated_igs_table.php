<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStoreIdOnUpdatedIgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('updated_igs', function (Blueprint $table) {
            $table->integer('store_id')->nullable()->after('storeid');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('updated_igs', function (Blueprint $table) {
            $table->dropColumn(['store_id']);
        });
    }
}
