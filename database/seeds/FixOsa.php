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
        DB::table('item_inventories')->update(array('osa' => 0, 'oos' => 0));
        $items = ItemInventories::where('sapc', '>', 0)
            ->orWhere('whpc', '>', 0)
            ->orWhere('whcs', '>', 0)
            ->get();
        foreach ($items as $item) {
        	$item->osa = 1;
        	$item->update();
        }

        $items = ItemInventories::where('sapc', 0)
            ->where('whpc', 0)
            ->where('whcs', 0)
            ->get();
        foreach ($items as $item) {
            $item->oos = 1;
            $item->update();
        }
    }
}
