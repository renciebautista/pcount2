<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFixedOnStoreInventoriesTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_inventories', function (Blueprint $table) {
            $table->boolean('fixed')->after('transaction_date')->default(0);
        });
    }

    /*
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_inventories', function (Blueprint $table) {
            $table->dropColumn(['transaction_date']);
        });
    }
}
