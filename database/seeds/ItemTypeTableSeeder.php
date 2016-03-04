<?php

use Illuminate\Database\Seeder;

class ItemTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
		DB::table('item_types')->truncate();
	  	DB::statement("INSERT INTO item_types (id, type) VALUES        
	      (1,'MKL'),
	      (2,'ASSORTMENT');");
    }
}
