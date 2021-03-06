<?php

use Illuminate\Database\Seeder;
use App\Models\UpdateHash;


class UploadChannelItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        set_time_limit(0);
        $this->call(UploadChannelItemsTableSeeder::class);
        $this->call(UploadChannelAssormentTableSeeder::class);
        
        $hash = UpdateHash::find(1);
        if(empty($hash)){
            UpdateHash::create(['hash' => \Hash::make(date('Y-m-d H:i:s'))]);
        }else{
            $hash->hash = md5(date('Y-m-d H:i:s'));
            $hash->update();
        }
    }
}
