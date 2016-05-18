<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

use App\Models\Store;
use App\Models\StoreUser;


class ExportController extends Controller
{
    public function stores(){
        $fileName = "Store Mastefile.csv";
        $writer = WriterFactory::create(Type::CSV); // for CSV files
        $writer->openToBrowser($fileName); // stream data directly to the browser

        $writer->addRow(array('AREA', 'ENROLLMENT TYPE', 'DISTRIBUTOR CODE', 'DISTRIBUTOR', 'STOREID', 
                'CONCATENATED CODE', 'CONCATENATED CODE FOR PSUP', 'STORE NAME', 'CLIENT CODE', 'CLIENT NAME', 'CHANNEL CODE', 'CHANNEL NAME',
                'CUSTOMER CHAIN CODE', 'CUSTOMER CHAIN', 'REGION SHORT NAME', 'REGION NAME', 'REGION CODE', 'FMS', 'FMS USERNAME', 'AGENCY CODE',
                'AGENCY NAME', 'LEAD REFILLERS (FIRST NAME LAST NAME)', 'USER NAME', 'STATUS'));

        $stores = StoreUser::select(\DB::raw('area,enrollment,distributor,distributor_code,storeid,store_code,store_code_psup,store_name,
            client_code,client_name,channel_code,channel_desc,customer_code,customer_name,region_short,region,region_code,agency_code,agency_name,username'))
            ->join('stores', 'stores.id', '=', 'store_users.store_id')
            ->join('areas', 'areas.id', '=', 'stores.area_id')
            ->join('enrollments', 'enrollments.id', '=', 'stores.enrollment_id')
            ->join('distributors', 'distributors.id', '=', 'stores.distributor_id')
            ->join('clients', 'clients.id', '=', 'stores.client_id')
            ->join('channels', 'channels.id', '=', 'stores.channel_id')
            ->join('customers', 'customers.id', '=', 'stores.customer_id')
            ->join('regions', 'regions.id', '=', 'stores.region_id')
            ->join('agencies', 'agencies.id', '=', 'stores.agency_id')
            ->join('users', 'users.id', '=', 'store_users.user_id')
            ->get();
        // dd($stores->count());
        $pluckdata =[];
        foreach ($stores as $store) {
            $data[0] = $store->area;
            $data[1] = $store->enrollment;
            $data[2] = $store->distributor_code;
            $data[3] = $store->distributor;
            $data[4] = $store->storeid;
            $data[5] = $store->store_code;
            $data[6] = $store->store_code_psup;
            $data[7] = $store->store_name;
            $data[8] = $store->client_code;
            $data[9] = $store->client_name;
            $data[10] = $store->channel_code;
            $data[11] = $store->channel_desc;
            $data[12] = $store->customer_code;
            $data[13] = $store->customer_name;
            $data[14] = $store->region_short;
            $data[15] = $store->region;
            $data[16] = $store->region_code;
            $data[17] = '';
            $data[18] = '';
            $data[19] = $store->agency_code;
            $data[20] = $store->agency_name;
            $data[21] = '';
            $data[22] = $store->username;
            $data[23] = 'Active';
            $pluckdata[] = $data;
        }
        $writer->addRows($pluckdata); // add multiple rows at a time
        $writer->close();
    }
}
