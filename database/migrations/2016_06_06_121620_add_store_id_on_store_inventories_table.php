<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStoreIdOnStoreInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_inventories', function (Blueprint $table) {
             $table->integer('store_pri_id')->nullable()->after('store_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_inventories', function (Blueprint $table) {
            $table->dropColumn(['store_pri_id']);
        });
    }
}
