<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Models\UpdatedIg;
use App\Models\Store;

class UpdateIgStoreIdTableSeeder extends Seeder
{
    public function run()
    {
    	set_time_limit(0);
        ini_set('memory_limit', -1);
        $updated_igs = UpdatedIg::all();
        foreach ($updated_igs as $updated_ig) {
        	$store = Store::where('store_code',$updated_ig->store_code)->first();
        	if(!empty($store)){
        		$updated_ig->store_id = $store->id;
        		$updated_ig->save();
        	}
        }
    }
}
