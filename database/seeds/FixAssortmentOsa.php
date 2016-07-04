<?php

use Illuminate\Database\Seeder;

class FixAssortmentOsa extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('assortment_item_inventories')
    		->where('conversion', '>', 0)
            ->update(['oos' => 1, 'osa' => 0]);

    	DB::statement("update `assortment_item_inventories` set `oos` = 0, `osa` = 1 where `sapc` > min_stock");
    }
}
