<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

use App\Models\Division;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Item;

class UploadItemsTableSeeder extends Seeder
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

		DB::table('divisions')->truncate();
		DB::table('categories')->truncate();
		DB::table('sub_categories')->truncate();
		DB::table('brands')->truncate();
		DB::table('items')->truncate();


		foreach ($reader->getSheetIterator() as $sheet) {
			if($sheet->getName() == 'Items'){
				$cnt = 0;
				foreach ($sheet->getRowIterator() as $row) {

					if($row[0] != ''){
						if($cnt > 0){
							$division = Division::firstOrCreate(['division' => strtoupper($row[9])]);
							$category = Category::firstOrCreate(['category' => strtoupper($row[1]), 'category_long' => strtoupper($row[0])]);
							$sub_category = SubCategory::firstOrCreate(['category_id' => $category->id, 'sub_category' => strtoupper($row[7])]);
							$brand = Brand::firstOrCreate(['brand' => strtoupper($row[8])]);
							$item = Item::firstOrCreate([
								'sku_code' => trim($row[2]),
								'barcode' =>$row[3],
								'description' => strtoupper($row[4]),
								'description_long' => strtoupper($row[5]),
								'conversion' => trim($row[6]),
								'lpbt' => trim($row[10]),
								'division_id' => $division->id,
								'category_id' => $category->id,
								'sub_category_id' => $sub_category->id,
								'brand_id' => $brand->id
								]);
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
