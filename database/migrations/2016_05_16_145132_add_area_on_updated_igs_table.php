<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\UpdatedIg;
use App\Models\OtherBarcode;
use App\Models\Store;
use App\Models\Item;


class AddAreaOnUpdatedIgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('updated_igs', function (Blueprint $table) {
        //     $table->string('area')->after('id');
        //     $table->string('region_code')->after('area');
        //     $table->string('region')->after('region_code');
        //     $table->string('distributor_code')->after('region');
        //     $table->string('distributor')->after('distributor_code');
        //     $table->string('agency_code')->after('distributor');
        //     $table->string('agency')->after('agency_code');
        //     $table->string('storeid')->after('agency');
        //     $table->string('channel_code')->after('store_name');
        //     $table->string('channel')->after('channel_code');
        //     $table->string('other_code')->after('channel');
        // });

        set_time_limit(0);
        ini_set('memory_limit', -1);
        $updated_igs = UpdatedIg::all();

        foreach ($updated_igs as $updated_ig) {
            $store = Store::where('store_code', $updated_ig->store_code)->first();
            $item = Item::where('sku_code',  $updated_ig->sku_code)->first();
            if((!empty($store)) && (!empty($item))){
                $other_code = OtherBarcode::where('item_id', $item->id)
                    ->where('area_id', $store->area->id)
                    ->first();
                $updated_ig->area = $store->area->area;
                $updated_ig->region_code = $store->region->region_code;
                $updated_ig->region = $store->region->region;
                $updated_ig->distributor_code =  $store->distributor->distributor_code;
                $updated_ig->distributor = $store->distributor->distributor;
                $updated_ig->agency_code = $store->agency->agency_code;
                $updated_ig->agency = $store->agency->agency_name;
                $updated_ig->storeid = $store->storeid;
                $updated_ig->channel_code = $store->channel->channel_code;
                $updated_ig->channel = $store->channel->channel_desc;
                if(!empty($other_code)){
                     $updated_ig->other_code = $other_code->other_barcode;
                }
               
                $updated_ig->update();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('updated_igs', function (Blueprint $table) {
            $table->dropColumn(['area', 'region_code', 'region', 'distributor_code', 'distributor', 'agency_code', 'agency', 'storeid', 'channel_code', 'channel', 'other_code']);
        });
    }
}
