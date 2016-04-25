<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValidationOnSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['enable_item_validation']);
            $table->boolean('validate_posting_mkl')->default(1);
            $table->boolean('validate_printing_mkl')->default(1);
            $table->boolean('validate_posting_ass')->default(1);
            $table->boolean('validate_printing_ass')->default(1);
        });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {            
            $table->dropColumn(['validate_posting_mkl', 'validate_printing_mkl', 'validate_posting_ass', 'validate_printing_ass']);
            $table->boolean('enable_item_validation')->default(1);
        });    
    }
}
