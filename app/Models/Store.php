<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

use App\User;

class Store extends Model
{
    protected $fillable = ['storeid', 'store_code', 'store_code_psup', 
    'store_code_psup', 'area_id', 'enrollment_id', 'distributor_id', 
    'client_id', 'channel_id', 'customer_id', 'region_id', 'agency_id', 'active'];
    public $timestamps = false;

    public function area()
    {
        return $this->belongsTo('App\Models\Area');
    }

    public function enrollment()
    {
        return $this->belongsTo('App\Models\Enrollment');
    }

    public function distributor()
    {
        return $this->belongsTo('App\Models\Distributor');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function channel()
    {
        return $this->belongsTo('App\Models\Channel');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region');
    }

    public function agency()
    {
        return $this->belongsTo('App\Models\Agency');
    }

    public function status()
    {
        if($this->active){
            return 'Active';
        }else{
            return 'In-active';
        }
    }
   
    public static function search($request){
        return self::where('store_name', 'LIKE', "%$request->search%")
            ->where(function($query) use ($request){
            if(!empty($request->status)){
                    if($request->status == 1){
                        $query->where('active', 1);
                    }

                    if($request->status == 2){
                        $query->where('active',0);
                    }
                    
                }else{
                    $query->where('active', 1);
                }
            })
            ->paginate(100)

            ->appends(['search' => $request->search]);
    }

    public static function upload($filepath){
        \DB::beginTransaction();
         try {
            $reader = ReaderFactory::create(Type::XLSX); // for XLSX files
            $reader->open($filepath);
            $cnt = 0;
            Store::where('active',1)->update(['active' => 0]);
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    if($cnt > 0){
                        // dd($row);
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
                            // dd($row);
                            $user = User::firstOrCreate([
                            'username' => strtoupper($row[22]),
                            'name' => strtoupper($row[22]),
                            'email' => strtoupper($row[22]).'@pcount.com',
                            'password' => \Hash::make($row[22])]);

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
                    }
                    $cnt++;
                }
            }
            \DB::commit();
        } catch (Exception $e) {
            dd($e);
            \DB::rollback();
        }
    }
}
