<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDatabaseVersionToBackupListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('backup_lists', function (Blueprint $table) {
            $table->string('database_version')->after('filename');
          
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
            $table->dropColumn(['database_version']);
        });
    }
}
