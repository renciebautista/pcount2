<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\UpdatedIg;

class FixWrongIg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        set_time_limit(0);
        $cnt = 0;
        $updated_igs = UpdatedIg::where('updated_at', '>', '2016-05-24' )->get();
        foreach ($updated_igs as $updated_ig) {
            $item = DB::table('item_inventories')->join('store_inventories', 'store_inventories.id', '=', 'item_inventories.store_inventory_id')
                ->where('sku_code', $updated_ig->sku_code)
                ->where('store_code', $updated_ig->store_code)
                ->where('updated_at', '<', '2016-05-25')
                ->orderBy('updated_at','desc')
                ->first();
            if(!empty($item)){
                // dd($item);
                $updated_ig->ig = $item->ig;
                $updated_ig->updated_at = date('Y-m-d H:i:s');
                $updated_ig->update();
                $cnt++;
            }
        }

        echo $cnt;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
