<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBackupListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //


        Schema::create('backup_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename');
             $table->integer('device_backup_id')->unsigned();
              $table->foreign('device_backup_id')->references('id')->on('device_backups');
            $table->timestamps();
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
        Schema::table('backup_lists', function (Blueprint $table) {
            //

            
        });
    }
}
