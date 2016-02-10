<?php

use Illuminate\Database\Seeder;
use App\Models\ItemInventories;

class FixOsa extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// ItemInventories::all()->update(['osa' => 0]);
        $items = ItemInventories::where('so', 0)->get();
        foreach ($items as $item) {
        	$item->osa = 1;
        	$item->update();
        }
    }
}
