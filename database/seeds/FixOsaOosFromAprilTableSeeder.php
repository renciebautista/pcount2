<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Models\StoreInventories;
use App\Models\ItemInventories;

class FixOsaOosFromAprilTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
    	$list = ['MT CONVI', 'MT MINIMART', 'MT MDC'];
        $stores = StoreInventories::whereIn('client_name',$list)
        	->where('transaction_date', '>', '2016-03-31')
        	->get();

        foreach ($stores as $store) {
        	$items = ItemInventories::where('store_inventory_id',$store->id)->get();
        	
        	$client_name = $store->client_name;
        	foreach ($items as $item) {
        		$osa = 0;
                $oos = 0;
                $total_stockcs = $item->sapc + $item->whpc + ($item->whcs * $item->conversion);
                
        		if((strtoupper($client_name) == 'MT CONVI') || 
                    (strtoupper($client_name) == 'MT MINIMART') || 
                    (strtoupper($client_name) == 'MT MDC')
                    ){

                    if(strtoupper($client_name) == 'MT MDC'){
                        if($total_stockcs < 4){
                            $oos = 1;
                        }else{
                            $osa = 1;
                        }
                    }else{
                        if($total_stockcs < 3){
                            $oos = 1;
                        }else{
                            $osa = 1;
                        }
                    }
                }else{

                    if($total_stockcs > 0){
                        $osa = 1;
                    }else{
                        $oos = 1;
                    }
                }

                $item->oos = $oos;
                $item->osa = $osa;
                $item->update();
        	}
        }
    }
}
