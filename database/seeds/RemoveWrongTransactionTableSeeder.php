<?php

use Illuminate\Database\Seeder;

use App\Models\StoreInventories;
use App\Models\ItemInventories;
use App\Models\AssortmentInventories;
use App\Models\AssortmentItemInventories;

use Laracasts\TestDummy\Factory as TestDummy;

class RemoveWrongTransactionTableSeeder extends Seeder
{
    public function run()
    {
    	$storeInventories = StoreInventories::where('created_at', '>', '2016-05-25')->get();
    	foreach ($storeInventories as $storeinventory) {
    		ItemInventories::where('store_inventory_id', $storeinventory->id)->delete();
    		$storeinventory->delete();
    	}
        echo  'Total OSA Transaction deleted : ' . $storeInventories->count() . PHP_EOL;

        $assortmetnInventories = AssortmentInventories::where('created_at', '>', '2016-05-25')->get();
    	foreach ($assortmetnInventories as $assortmentinventory) {
    		AssortmentItemInventories::where('store_inventory_id', $assortmentinventory->id)->delete();
    		$assortmentinventory->delete();
    	}
        echo  'Total Assortment Transaction deleted : ' . $assortmetnInventories->count() . PHP_EOL;

        DB::table('updated_igs')->truncate();
    }
}
