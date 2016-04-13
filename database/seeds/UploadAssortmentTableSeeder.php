<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

use App\Models\Channel;
use App\Models\Customer;
use App\Models\Store;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\StoreItem;


class UploadAssortmentTableSeeder extends Seeder
{    
    public function run()
    {
        Model::unguard();

		DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		$folderpath = base_path().'/database/seeds/seed_files/';
		$folders = File::directories($folderpath);
		$latest = '11232015';
		foreach ($folders as $value) {
			$_dir = explode("/", str_replace('\\', '/', $value));
			$cnt = count($_dir);
			$name = $_dir[$cnt - 1];
			$latest_date = DateTime::createFromFormat('mdY', $latest);					
			$now = DateTime::createFromFormat('mdY', $name);	
			if($now > $latest_date){
				$latest = $name;
			}
		}
		$filePath = $folderpath.$latest.'/Masterfile.xlsx';

		$reader = ReaderFactory::create(Type::XLSX); // for XLSX files
		$reader->open($filePath);

		DB::table('store_items');

		foreach ($reader->getSheetIterator() as $sheet) {
			if($sheet->getName() == 'Assortment Mapping'){
				$cnt = 0;
				foreach ($sheet->getRowIterator() as $row) {
					if($row[0] != ''){
						if($cnt > 0){
							$channel = '';
							$customer = '';
							$store = '';
							if(trim($row[0]) != "All Channels"){
								$channel = Channel::where('channel_code', trim($row[0]))->first();
							}
							if(trim($row[1]) != "All Customers"){
								$customer = Customer::where('customer_code', trim($row[1]))->first();
							}
							if(trim($row[2]) != "All Stores"){
								$store = Store::where('store_code', trim($row[2]))->first();
							}

							$stores = Store::where(function($query) use ($channel){
								if(!empty($channel)){
										$query->where('channel_id',$channel->id);
									}
								})
								->where(function($query) use ($customer){
								if(!empty($customer)){
										$query->where('customer_id',$customer->id);
									}
								})
								->where(function($query) use ($store){
								if(!empty($store)){
										$query->where('store',$store->id);
									}
								})
								->get();
								
							$item = Item::where('sku_code', trim($row[3]))->first();
							if(!empty($item)){
								$item_type = ItemType::where('type',"ASSORTMENT")->first();
								foreach ($stores as $store) {
									$w_mkl = StoreItem::where('store_id',$store->id)->where('item_id',$item->id)->get();
									if(count($w_mkl) == 0){
										StoreItem::firstOrCreate([
											'store_id' => $store->id,
											'item_id' => $item->id,
											'item_type_id' =>$item_type->id,
											'ig' => trim($row[4]),
											'fso_multiplier' => trim($row[5]),
											'min_stock' => trim($row[6])
										]);
									}
									
								}
							}
							
							
						}
						$cnt++;	
						
					}
				}
			}
		}

		$reader->close();

		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		Model::reguard();
    }
}
