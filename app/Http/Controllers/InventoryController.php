<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\ItemInventories;

use App\Models\StoreInventories;
use App\Models\AssortmentInventories;
use App\Models\AssortmentItemInventories;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;



class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type = null)
    {
       
        $frm = date("m-d-Y");
        $to = date("m-d-Y");    

        $report_type = 1;
        if((is_null($type)) || ($type != 'assortment')){
            $report_type = 2;
        }

        $sel_ag = []; 
        $sel_cl = []; 
        $sel_ch = [];   
        $sel_ds = []; 
        $sel_en = []; 
        $sel_rg = []; 
        $sel_st = [];

        $sel_dv = [];
        $sel_cat = [];
        $sel_scat = [];
        $sel_br = [];
        $sel_tag = [];
          $sel_av = [];
        $tags = ['1' => 'OSA', '2' => 'NPI'];
        $availability =['1'=>'oos','2'=>'osa'];
        if($report_type == 2){
            $agencies = StoreInventories::getAgencyList();
            // $sel_ag = StoreInventories::getStoreCodes('agency_code'); 
            // $sel_cl = StoreInventories::getStoreCodes('client_code');    
            // $sel_ch = StoreInventories::getStoreCodes('channel_code');    
            // $sel_ds = StoreInventories::getStoreCodes('distributor_code');  
            // $sel_en = StoreInventories::getStoreCodes('enrollment_type'); 
            // $sel_rg = StoreInventories::getStoreCodes('region_code'); 
            // $sel_st = StoreInventories::getStoreCodes('store_id');

            $divisions = ItemInventories::getDivisionList();
            // $sel_dv = ItemInventories::getItemCodes('division');
            // $sel_cat = ItemInventories::getItemCodes('category');
            // $sel_scat = ItemInventories::getItemCodes('sub_category');
            // $sel_br = ItemInventories::getItemCodes('brand');
        }else{
            $agencies = AssortmentInventories::getAgencyList();
            // $sel_ag = AssortmentInventories::getStoreCodes('agency_code'); 
            // $sel_cl = AssortmentInventories::getStoreCodes('client_code');    
            // $sel_ch = AssortmentInventories::getStoreCodes('channel_code');    
            // $sel_ds = AssortmentInventories::getStoreCodes('distributor_code');  
            // $sel_en = AssortmentInventories::getStoreCodes('enrollment_type'); 
            // $sel_rg = AssortmentInventories::getStoreCodes('region_code'); 
            // $sel_st = AssortmentInventories::getStoreCodes('store_id');

            $divisions = AssortmentItemInventories::getDivisionList();
            // $sel_dv = AssortmentItemInventories::getItemCodes('division');
            // $sel_cat = AssortmentItemInventories::getItemCodes('category');
            // $sel_scat = AssortmentItemInventories::getItemCodes('sub_category');
            // $sel_br = AssortmentItemInventories::getItemCodes('brand');
        }
        

        $data = array();

        if(!empty($sel_ag)){
            $data['agencies'] = $sel_ag;
        }
        if(!empty($sel_cl)){
            $data['clients'] = $sel_cl;
        }
        if(!empty($sel_ch)){
            $data['channels'] = $sel_ch;
        }
        if(!empty($sel_ds)){
            $data['distributors'] = $sel_ds;
        }
        if(!empty($sel_en)){
            $data['enrollments'] = $sel_en;
        }
        if(!empty($sel_rg)){
            $data['regions'] = $sel_rg;
        }
        if(!empty($sel_st)){
            $data['stores'] = $sel_st;
        }
        if(!empty($sel_dv)){
            $data['divisions'] = $sel_dv;
        }
        if(!empty($sel_cat)){
            $data['categories'] = $sel_cat;
        }
        if(!empty($sel_scat)){
            $data['sub_categories'] = $sel_scat;
        }
        if(!empty($sel_br)){
            $data['brands'] = $sel_br;
        }
        if(!empty($frm)){
            $data['from'] = $frm;
        }
        if(!empty($to)){
            $data['to'] = $to;
        }

        if($report_type == 2){
            $items = ItemInventories::filter($data);
            $header = "MKL Posted Transaction Report";
        }else{
            $items = AssortmentItemInventories::filter($data);
            $header = "Assortment Posted Transaction Report";
        }
        

        return view('inventory.index',compact('frm', 'to', 'agencies','sel_ag',
            'sel_cl', 'sel_ch', 'sel_ds', 'sel_en', 'sel_rg', 'sel_st',
            'divisions', 'sel_dv', 'sel_cat', 'sel_scat', 'sel_br' ,'items', 'header','type', 'sel_tag', 'tags','availability','sel_av'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $type = null)
    {
        $sel_ag = $request->ag;
        $sel_cl = $request->cl;
        $sel_ch = $request->ch;
        $sel_ds = $request->ds;
        $sel_en = $request->en;
        $sel_rg = $request->rg;
        $sel_st = $request->st;
        $sel_tag = $request->tags;
        $sel_av = $request->availability;
        $tags = ['1' => 'OSA', '2' => 'NPI'];
  $availability =['1'=>'oos','2'=>'osa'];
        $report_type = 1;
        if((is_null($type)) || ($type != 'assortment')){
            $report_type = 2;
        }

        if($report_type == 2){
            $agencies = StoreInventories::getAgencyList();
            $divisions = ItemInventories::getDivisionList();
        }else{
            $agencies = AssortmentInventories::getAgencyList();
            $divisions = AssortmentItemInventories::getDivisionList();
        }
        
        $sel_dv = $request->dv;
        $sel_cat = $request->ct;
        $sel_scat = $request->sc;
        $sel_br = $request->br;

        $frm = $request->fr;
        $to = $request->to;
        
        $data = array();

        if(!empty($sel_ag)){
            $data['agencies'] = $sel_ag;
        }
        if(!empty($sel_cl)){
            $data['clients'] = $sel_cl;
        }
        if(!empty($sel_ch)){
            $data['channels'] = $sel_ch;
        }
        if(!empty($sel_ds)){
            $data['distributors'] = $sel_ds;
        }
        if(!empty($sel_en)){
            $data['enrollments'] = $sel_en;
        }
        if(!empty($sel_rg)){
            $data['regions'] = $sel_rg;
        }
        if(!empty($sel_st)){
            $data['stores'] = $sel_st;
        }
        if(!empty($sel_dv)){
            $data['divisions'] = $sel_dv;
        }
        if(!empty($sel_cat)){
            $data['categories'] = $sel_cat;
        }
        if(!empty($sel_scat)){
            $data['sub_categories'] = $sel_scat;
        }
        if(!empty($sel_br)){
            $data['brands'] = $sel_br;
        }
        if(!empty($frm)){
            $data['from'] = $frm;
        }
        if(!empty($to)){
            $data['to'] = $to;
        }
        if(!empty($sel_tag)){
            $data['tags'] = $sel_tag;
        }
    if(!empty($sel_av)){
            $data['availability'] = $sel_av;
        }
        if($report_type == 2){
            $items = ItemInventories::filter($data);
            $header = "MKL Posted Transaction Report";
        }else{
            $items = AssortmentItemInventories::filter($data);
            $header = "Assortment Posted Transaction Report";
        }

        if ($request->has('submit')) {
       
            return view('inventory.index',compact('frm', 'to', 'agencies','sel_ag',
            'sel_cl', 'sel_ch', 'sel_ds', 'sel_en', 'sel_rg', 'sel_st',
            'divisions', 'sel_dv', 'sel_cat', 'sel_scat', 'sel_br' ,'items', 'header','type', 'sel_tag', 'tags','sel_av','availability'));
        }

        set_time_limit(0);
        if ($request->has('download')) {

            $take = 1000; // adjust this however you choose
            $skip = 0; // used to skip over the ones you've already processed

            $writer = WriterFactory::create(Type::CSV);
            $writer->openToBrowser($header.'.csv');
            $writer->addRow(array('AREA', 'REGION', 'DISTRIBUTOR', 'DISTRIBUTOR CODE', 'STORE ID', 
                'STORE CODE', 'STORE NAME', 'OTHER CODE', 'SKU CODE', 'DIVISION', 'BRAND', 'CATEGORY', 'SUB CATEGORY',
                'ITEM DESCRIPTION', 'IG', 'FSO MULTIPLIER', 'SAPC',
                'WHPC', 'WHCS', 'SO', 'FSO', 'FSO VAL', 'OSA', 'OOS', 'TRANSACTION DATE', 'POSTING DATE AND TIME', 'SIGNATURE LINK'));

            if($report_type == 2){
                while($rows = ItemInventories::getPartial($data,$take,$skip))
                {
                    if(count($rows) == 0){
                        break;
                    }
                    $skip ++;
                    $plunck_data = [];
                    foreach($rows as $row)
                    {
                        if(!is_null($row->signature)){
                            if($report_type == 2){
                                $link = url('api/pcountimage', [$row->signature]);
                            }else{
                                $link = url('api/assortmentimage', [$row->signature]);
                            }
                            
                        }else{
                            $link = '';
                        }
                        $row_data[0] = $row->area;
                        $row_data[1] = $row->region_name;
                        $row_data[2] = $row->distributor;
                        $row_data[3] = $row->distributor_code;
                        $row_data[4] = $row->store_id;
                        $row_data[5] = $row->store_code;
                        $row_data[6] = $row->store_name;
                        $row_data[7] = $row->other_barcode;
                        $row_data[8] = $row->sku_code;
                        $row_data[9] = $row->division;
                        $row_data[10] = $row->brand;
                        $row_data[11] = $row->category;
                        $row_data[12] = $row->sub_category;
                        $row_data[13] = $row->description;
                        $row_data[14] = $row->ig;
                        $row_data[15] = $row->fso_multiplier;
                        $row_data[16] = $row->sapc;
                        $row_data[17] = $row->whpc;
                        $row_data[18] = $row->whcs;
                        $row_data[19] = $row->so;
                        $row_data[20] = $row->fso;
                        $row_data[21] = (double)$row->fso_val;
                        $row_data[22] = $row->osa;
                        $row_data[23] = $row->oos;
                        $row_data[24] = $row->transaction_date;
                        $row_data[25] = $row->created_at;
                        $row_data[26] = $link;
                        $plunck_data[] = $row_data;
                    }

                    $writer->addRows($plunck_data); // add multiple rows at a time
                    
                }
            }else{
                while($rows = AssortmentItemInventories::getPartial($data,$take,$skip))
                {
                    if(count($rows) == 0){
                        break;
                    }
                    $skip ++;
                    $plunck_data = [];
                    foreach($rows as $row)
                    {
                        if(!is_null($row->signature)){
                            if($report_type == 2){
                                $link = url('api/pcountimage', [$row->signature]);
                            }else{
                                $link = url('api/assortmentimage', [$row->signature]);
                            }
                            
                        }else{
                            $link = '';
                        }
                        $row_data[0] = $row->area;
                        $row_data[1] = $row->region_name;
                        $row_data[2] = $row->distributor;
                        $row_data[3] = $row->distributor_code;
                        $row_data[4] = $row->store_id;
                        $row_data[5] = $row->store_code;
                        $row_data[6] = $row->store_name;
                        $row_data[7] = $row->other_barcode;
                        $row_data[8] = $row->sku_code;
                        $row_data[9] = $row->division;
                        $row_data[10] = $row->brand;
                        $row_data[11] = $row->category;
                        $row_data[12] = $row->sub_category;
                        $row_data[13] = $row->description;
                        $row_data[14] = $row->ig;
                        $row_data[15] = $row->fso_multiplier;
                        $row_data[16] = $row->sapc;
                        $row_data[17] = $row->whpc;
                        $row_data[18] = $row->whcs;
                        $row_data[19] = $row->so;
                        $row_data[20] = $row->fso;
                        $row_data[21] = (double)$row->fso_val;
                        $row_data[22] = $row->osa;
                        $row_data[23] = $row->oos;
                        $row_data[24] = $row->transaction_date;
                        $row_data[25] = $row->created_at;
                        $row_data[26] = $link;
                        $plunck_data[] = $row_data;
                    }

                    $writer->addRows($plunck_data); // add multiple rows at a time
                    
                }
            }
            

            $writer->close();
        }
    }

}
