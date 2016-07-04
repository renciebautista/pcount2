<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMinStocksOnItemInventories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_inventories', function (Blueprint $table) {
            $table->integer('min_stock')->after('ig')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_inventories', function (Blueprint $table) {
            $table->dropColumn(['min_stock']);
        });
    }
}
