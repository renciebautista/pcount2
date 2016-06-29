<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

use App\Models\Item;
use App\Models\Area;
use App\Models\OtherBarcode;

class UploadOtherBarcodesTableSeeder extends Seeder
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

		// DB::table('other_barcodes')->truncate();


		foreach ($reader->getSheetIterator() as $sheet) {
			if($sheet->getName() == 'Other Codes'){
				$cnt = 0;
				foreach ($sheet->getRowIterator() as $row) {
					if((!is_null($row[0])) && (trim($row[0]) != '')){
						if($cnt > 0){
							$item = Item::where('sku_code', trim($row[0]))->first();
							if(!empty($item)){
								if($item->cleared == 0){
									OtherBarcode::where('item_id', $item->id)->delete();
									$item->cleared = 1;
									$item->save();
								}
								$area = Area::where('area', strtoupper($row[1]))->first();

								if((!empty($item)) && (!empty($area))){
									OtherBarcode::firstOrCreate([
										'item_id' => $item->id,
										'area_id' => $area->id,
										'other_barcode' => trim($row[2])
									]);
								}
							}else{
								// dd($row);
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
