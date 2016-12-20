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
use App\Role;
use App\Models\StoreUser;
use App\Models\InvalidStore;

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
			$_dir = explode("/", str_replace('\\', '/', $value));
			$cnt = count($_dir);
			$name = $_dir[$cnt - 1];
			$latest_date = DateTime::createFromFormat('mdY', $latest);
			$now = DateTime::createFromFormat('mdY', $name);
			if($now > $latest_date){
				$latest = $name;

			}
		}
			echo $latest;
		$filePath = $folderpath.$latest.'/Masterfile.xlsx';
		$reader = ReaderFactory::create(Type::XLSX); // for XLSX files
		$reader->open($filePath);
		echo 'Seeding '. $filePath. PHP_EOL;
		// DB::table('areas')->truncate();
		// DB::table('enrollments')->truncate();
		// DB::table('distributors')->truncate();
		// DB::table('clients')->truncate();
		// DB::table('channels')->truncate();
		// DB::table('agencies')->truncate();
		// DB::table('regions')->truncate();
		// DB::table('customers')->truncate();
		// DB::table('stores')->truncate();
		// DB::table('invalid_stores')->truncate();
		// DB::table('store_users')->truncate();
		// $role = Role::find(2)->users()->delete();

		// dd($role);

	   // add masterfiles
		foreach ($reader->getSheetIterator() as $sheet) {
			if($sheet->getName() == 'Stores'){
				$cnt = 0;
				Store::where('active',1)->update(['active' => 0]);
				foreach ($sheet->getRowIterator() as $row) {

					if($row[0] != ''){
						if($cnt > 0){
							// if(strtoupper($row[23]) == 'INACTIVE'){
							// 	InvalidStore::invalid($row,'Inactive Store');
							// }else{
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

									$user->roles()->attach(2);
								}
								$storeExist = Store::where('store_code',strtoupper($row[5]))->first();
								if((empty($storeExist)) && (!empty($row[22]))){
									$store = Store::create([
										'storeid' => strtoupper($row[4]),
										'store_code' => strtoupper($row[5]),
										'store_code_psup' => strtoupper($row[6]),
										'store_name' => strtoupper($row[7]),
										'area_id' => $area->id,
										'enrollment_id' => $enrollment->id,
										'distributor_id' => $distributor->id,
										'client_id' => $client->id,
										'channel_id' => $channel->id,
										'customer_id' => $customer->id,
										'region_id' => $region->id,
										'agency_id' => $agency->id,
										'active' => 1
										]);
									if(!empty($row[22])){
										StoreUser::insert(['store_id' => $store->id, 'user_id' => $user->id]);
									}
								}else{
									// InvalidStore::invalid($row,'Duplicate Store Code');
									$storeExist->storeid = strtoupper($row[4]);
		                            $storeExist->store_code = strtoupper($row[5]);
		                            $storeExist->store_code_psup = strtoupper($row[6]);
		                            $storeExist->store_name = strtoupper($row[7]);
		                            $storeExist->area_id = $area->id;
		                            $storeExist->enrollment_id = $enrollment->id;
		                            $storeExist->distributor_id = $distributor->id;
		                            $storeExist->client_id = $client->id;
		                            $storeExist->channel_id = $channel->id;
		                            $storeExist->customer_id = $customer->id;
		                            $storeExist->region_id = $region->id;
		                            $storeExist->agency_id = $agency->id;
		                            $storeExist->active = 1;
		                            $storeExist->save();

		                            StoreUser::where('store_id',$storeExist->id)->delete();
		                            StoreUser::insert(['store_id' => $storeExist->id, 'user_id' => $user->id]);
								}
							// }


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
