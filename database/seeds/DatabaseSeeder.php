<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

         // $this->call(RolesTableSeeder::class);
         // $this->call(AddAdminTableSeeder::class);

        
        $this->call(UploadStoresTableSeeder::class);
        $this->call(UploadItemsTableSeeder::class);
        $this->call(UploadOtherBarcodesTableSeeder::class);
        $this->call(UploadStoreItemsTableSeeder::class);
        
        Model::reguard();
    }
}
