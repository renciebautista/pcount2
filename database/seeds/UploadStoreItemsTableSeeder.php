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
use App\Models\InvalidMapping;


class UploadStoreItemsTableSeeder extends Seeder
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
		DB::table('invalid_mappings')->truncate();
		DB::table('store_items')->truncate();

		foreach ($reader->getSheetIterator() as $sheet) {
			if($sheet->getName() == 'MKL Mapping'){
				$cnt = 0;
				foreach ($sheet->getRowIterator() as $row) {
					if($row[0] != ''){
						if($cnt > 0){

							// dd($row);
							if((!ctype_digit(trim($row[4]))) || (!ctype_digit(trim($row[5]))) ||(!ctype_digit(trim($row[6]))) ){
								InvalidMapping::create([
									'premise_code' => trim($row[0]),
									'customer_code' => trim($row[1]),
									'store_code' => trim($row[2]),
									'sku_code' => trim($row[3]),
									'ig' => trim($row[4]),
									'multiplier' => trim($row[5]),
									'minstock' => trim($row[6]),
									'type' => 'MKL Mapping',
									'remarks' => 'Invalid mapping',
									]);
							}else{
								$channel = '';
								$customer = '';
								$store = '';
								if(trim($row[0]) != ''){
									$channel = Channel::where('channel_code', trim($row[0]))->get();
								}
								if(trim($row[1]) != ''){
									$customer = Customer::where('customer_code', trim($row[1]))->get();
								}
								if(trim($row[2]) != ''){
									$store = Store::where('store_code', trim($row[2]))->first();
								}

								// dd($customer);
								$stores = Store::where(function($query) use ($channel){
									if(!empty($channel)){
											$channel_id = [];
											foreach ($channel as $value) {
												$channel_id[] = $value->id;
											}
											$query->whereIn('channel_id',$channel_id);
										}
									})
									->where(function($query) use ($customer){
									if(!empty($customer)){
											$customer_id = [];
											foreach ($customer as $value) {
												$customer_id[] = $value->id;
											}
											$query->whereIn('customer_id',$customer_id);
										}
									})
									->where(function($query) use ($store){
									if(!empty($store)){
											$query->where('store',$store->id);
										}
									})
									->get();
								// dd($stores);
								$item = Item::where('sku_code', trim($row[3]))->first();
								if(!empty($item)){
									$item_type = ItemType::where('type',"MKL")->first();							
									foreach ($stores as $store) {
										$osa_tagging = 0;
										if(isset($row[7])){
											$osa_tagging = trim($row[7]);
										}
										$npi_tagging = 0;
										if(isset($row[8])){
											$npi_tagging = trim($row[8]);
										}

										StoreItem::firstOrCreate([
											'store_id' => $store->id,
											'item_id' => $item->id,
											'item_type_id' => $item_type->id,
											'ig' => trim($row[4]),
											'fso_multiplier' => trim($row[5]),
											'min_stock' => trim($row[6]),
											'osa_tagged' => $osa_tagging,
											'npi_tagged' => $npi_tagging
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
