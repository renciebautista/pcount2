<?php

use Illuminate\Database\Seeder;
use App\Models\UpdateHash;

class UpdateMasterfile extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	set_time_limit(0);
        $this->call(UploadStoresTableSeeder::class);
        $this->call(UploadItemsTableSeeder::class);
        $this->call(UploadOtherBarcodesTableSeeder::class);
        $this->call(UploadStoreItemsTableSeeder::class);
        $this->call(UploadAssortmentTableSeeder::class);
        // $this->call(UpdateStoreItemIgTableSeeder::class);

        $hash = UpdateHash::find(1);
        if(empty($hash)){
            UpdateHash::create(['hash' => \Hash::make(date('Y-m-d H:i:s'))]);
        }else{
            $hash->hash = md5(date('Y-m-d H:i:s'));
            $hash->update();
        }
    }
}
