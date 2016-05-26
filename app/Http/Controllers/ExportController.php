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
use App\Models\Item;
use App\Models\OtherBarcode;
use App\Models\StoreItem;


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

    public function items(){
        $fileName = "Items Mastefile.csv";
        $writer = WriterFactory::create(Type::CSV); // for CSV files
        $writer->openToBrowser($fileName); // stream data directly to the browser

        $writer->addRow(array('Category Long Description', 'Category Short Description', 'SKU Code', 'Barcode', 'Item Short Description', 
                'Item Long Description', 'Conversion', 'Sub Category', 'Brand', 'Division', 'LPBT/Cond Value (PC)'));

        $items = Item::select(\DB::raw('category_long, category, sku_code, barcode, description, 
            description_long, conversion, sub_category, brand, division, lpbt'))
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('sub_categories', 'sub_categories.id', '=', 'items.sub_category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->join('divisions', 'divisions.id', '=', 'items.division_id')
            ->get();
        $pluckdata =[];
        foreach ($items as $item) {
            $data[0] = $item->category_long;
            $data[1] = $item->category;
            $data[2] = $item->sku_code;
            $data[3] = $item->barcode;
            $data[4] = $item->description;
            $data[5] = $item->description_long;
            $data[6] = $item->conversion;
            $data[7] = $item->sub_category;
            $data[8] = $item->brand;
            $data[9] = $item->division;
            $data[10] = $item->lpbt;
            $pluckdata[] = $data;
        }
        $writer->addRows($pluckdata); // add multiple rows at a time
        $writer->close();
    }

    public function othercode(){
        $fileName = "Item Other Code Masterfile.csv";
        $writer = WriterFactory::create(Type::CSV); // for CSV files
        $writer->openToBrowser($fileName); // stream data directly to the browser

        $writer->addRow(array('Sku Code', 'Area', 'Other Code'));

        $items = OtherBarcode::select(\DB::raw('sku_code, area, other_barcode'))
            ->join('items', 'items.id', '=', 'other_barcodes.item_id')
            ->join('areas', 'areas.id', '=', 'other_barcodes.area_id')
            ->orderBy('sku_code')
            ->get();
        $pluckdata =[];
        foreach ($items as $item) {
            $data[0] = $item->sku_code;
            $data[1] = $item->area;
            $data[2] = $item->other_barcode;
            $pluckdata[] = $data;
        }
        $writer->addRows($pluckdata); // add multiple rows at a time
        $writer->close();
    }

    public function storeosa(){
        $take = 1000; // adjust this however you choose
        $skip = 0; // used to skip over the ones you've already processed

        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Store OSA Item Masterfile.csv');
        $writer->addRow(array('STORE CODE', 'STORE NAME', 'SKU CODE', 'BARCODE', 'ITEM DESCRIPTION', 'IG', 'FSO MULTIPLIER', 'MIN STOCK'));
        set_time_limit(0);
        while($rows = StoreItem::getPartial($take,$skip,1))
        {
            if(count($rows) == 0){
                break;
            }
            $skip ++;
            $plunck_data = [];
            foreach($rows as $row)
            {
                $row_data[0] = $row->store_code;
                $row_data[1] = $row->store_name;
                $row_data[2] = $row->sku_code;
                $row_data[3] = $row->barcode;
                $row_data[4] = $row->description;
                $row_data[5] = $row->ig;
                $row_data[6] = $row->fso_multiplier;
                $row_data[7] = $row->min_stock;
                $plunck_data[] = $row_data;
            }
            $writer->addRows($plunck_data); 
        }
        $writer->close();
    }

    public function storeassortment(){
        $take = 1000; // adjust this however you choose
        $skip = 0; // used to skip over the ones you've already processed

        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Store Assortment Masterfile.csv');
        $writer->addRow(array('STORE CODE', 'STORE NAME', 'SKU CODE', 'BARCODE', 'ITEM DESCRIPTION', 'IG', 'FSO MULTIPLIER', 'MIN STOCK'));
        set_time_limit(0);
        while($rows = StoreItem::getPartial($take,$skip,2))
        {
            if(count($rows) == 0){
                break;
            }
            $skip ++;
            $plunck_data = [];
            foreach($rows as $row)
            {
                $row_data[0] = $row->store_code;
                $row_data[1] = $row->store_name;
                $row_data[2] = $row->sku_code;
                $row_data[3] = $row->barcode;
                $row_data[4] = $row->description;
                $row_data[5] = $row->ig;
                $row_data[6] = $row->fso_multiplier;
                $row_data[7] = $row->min_stock;
                $plunck_data[] = $row_data;
            }
            $writer->addRows($plunck_data); 
        }
        $writer->close();
    }
}
