<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValidateRepostingToSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('settings', function (Blueprint $table) {
         
            $table->boolean('validate_reposting_mkl')->default(1); 
            $table->boolean('validate_reposting_ass')->default(1);
        });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
         Schema::table('settings', function (Blueprint $table) {            
            $table->dropColumn(['validate_reposting_mkl', 'validate_reposting_ass']);
             }); 
    }
}
