<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;	

use App\Models\Area;
use App\Models\Enrollment;
use App\Models\Distributor;
use App\Models\Client;
use App\Models\Channel;
use App\Models\Agency;
use App\Models\Region;
use App\Models\Customer;
use App\Models\Store;
use App\User;
use App\Models\StoreUser;

class UploadStoresTableSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

		DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		$folderpath = base_path().'/database/seeds/seed_files/';
		$folders = File::directories($folderpath);
		$latest = '11232015';
		foreach ($folders as $value) {
			$_dir = explode("/", $value);
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

		DB::table('areas')->truncate();
		DB::table('enrollments')->truncate();
		DB::table('distributors')->truncate();
		DB::table('clients')->truncate();
		DB::table('channels')->truncate();
		DB::table('agencies')->truncate();
		DB::table('regions')->truncate();
		DB::table('customers')->truncate();
		DB::table('stores')->truncate();
		DB::table('users')->truncate();
		DB::table('store_users')->truncate();


	   // add masterfiles
		foreach ($reader->getSheetIterator() as $sheet) {
			if($sheet->getName() == 'Stores'){
				$cnt = 0;
				foreach ($sheet->getRowIterator() as $row) {
					// dd($row);
					if(!is_null($row[0])){
						if($cnt > 0){
							$area = Area::firstOrCreate(['area' => strtoupper($row[0])]);
							$enrollment = Enrollment::firstOrCreate(['enrollment' => strtoupper($row[1])]);
							$distributor = Distributor::firstOrCreate(['distributor_code' => strtoupper($row[2]), 'distributor' => strtoupper($row[3])]);
							$client = Client::firstOrCreate(['client_code' => strtoupper($row[8]), 'client_name' => strtoupper($row[9])]);
							$channel = Channel::firstOrCreate(['channel_code' => strtoupper($row[10]), 'channel_desc' => strtoupper($row[11])]);
							$agency = Agency::firstOrCreate(['agency_code' => strtoupper($row[19]), 'agency_name' => strtoupper($row[20])]);
							$region = Region::firstOrCreate(['region_code' => strtoupper($row[16]), 'region' => strtoupper($row[15]), 'region_short' => strtoupper($row[14])]);
							$customer = Customer::firstOrCreate(['customer_code' => strtoupper($row[12]), 'customer_name' => strtoupper($row[13])]);

							$user = User::where('username',strtoupper($row[22]))->first();
							if((empty($user)) && (!empty($row[22]))){
								$user = User::firstOrCreate([
								'username' => strtoupper($row[22]),
								'name' => strtoupper($row[22]),
								'email' => strtoupper($row[22]).'@pcount.com',
								'password' => Hash::make($row[22])]);
							}

							$store = Store::firstOrCreate([
								'storeid' => strtoupper($row[4]),
								'store_code' => strtoupper($row[5]),
								'store_code_psup' => strtoupper($row[6	]),
								'store_name' => strtoupper($row[7]),
								'area_id' => $area->id,
								'enrollment_id' => $enrollment->id,
								'distributor_id' => $distributor->id,
								'client_id' => $client->id,
								'channel_id' => $channel->id,
								'customer_id' => $customer->id,
								'region_id' => $region->id,
								'agency_id' => $agency->id
								]);
							if(!empty($row[22])){
								StoreUser::insert(['store_id' => $store->id, 'user_id' => $user->id]);
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
