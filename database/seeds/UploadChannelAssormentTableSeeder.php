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
use App\Models\ChannelItem;

class UploadChannelAssormentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Model::unguard();

		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$folderpath = base_path().'/database/seeds/templates/';
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
	
		foreach ($reader->getSheetIterator() as $sheet) {
			if($sheet->getName() == 'Assortment Mapping'){
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
									'type' => 'Assortment Mapping',
									'remarks' => 'Invalid mapping',
									]);
							}
							else {
								$channel = '';
								
								if(trim($row[0]) != '') {
									$channel = Channel::where('channel_code', trim($row[0]))->first();
								}
								

								$item = Item::where('sku_code', trim($row[3]))->first();
								if(!empty($item)) {

										$item_type = ItemType::where('type',"ASSORTMENT")->first();
										$cw_mkl = ChannelItem::where('channel_id',$channel->id)->where('item_id',$item->id)->get();
											
										if(count($cw_mkl) == 0){
										
			                   				 ChannelItem::firstOrCreate([
			                      				'channel_id' => $channel->id,
			                      				'item_id' => $item->id,
			                      				'item_type_id' => $item_type->id,
			                      				'ig' => trim($row[4]),
			                      				'fso_multiplier' => trim($row[5]),
			                      				'min_stock' => trim($row[6]),
			                      				'osa_tagged' => 0,
			                      				'npi_tagged' => 0
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







    }
}
