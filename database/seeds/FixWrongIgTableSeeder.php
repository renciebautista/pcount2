<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Models\StoreItem;

class FixWrongIgTableSeeder extends Seeder
{
    public function run()
    {
        $store_items = StoreItem::where('ig_updated',1)->get();
        echo $store_items->count().PHP_EOL; 

        foreach ($store_items as $store_item) {
        	# c
        }
    }
}
