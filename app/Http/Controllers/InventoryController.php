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

        if($report_type == 2){
            $agencies = StoreInventories::getAgencyList();
            $sel_ag = StoreInventories::getStoreCodes('agency_code'); 
            $sel_cl = StoreInventories::getStoreCodes('client_code');    
            $sel_ch = StoreInventories::getStoreCodes('channel_code');    
            $sel_ds = StoreInventories::getStoreCodes('distributor_code');  
            $sel_en = StoreInventories::getStoreCodes('enrollment_type'); 
            $sel_rg = StoreInventories::getStoreCodes('region_code'); 
            $sel_st = StoreInventories::getStoreCodes('store_id');

            $divisions = ItemInventories::getDivisionList();
            $sel_dv = ItemInventories::getItemCodes('division');
            $sel_cat = ItemInventories::getItemCodes('category');
            $sel_scat = ItemInventories::getItemCodes('sub_category');
            $sel_br = ItemInventories::getItemCodes('brand');
        }else{
            $agencies = AssortmentInventories::getAgencyList();
            $sel_ag = AssortmentInventories::getStoreCodes('agency_code'); 
            $sel_cl = AssortmentInventories::getStoreCodes('client_code');    
            $sel_ch = AssortmentInventories::getStoreCodes('channel_code');    
            $sel_ds = AssortmentInventories::getStoreCodes('distributor_code');  
            $sel_en = AssortmentInventories::getStoreCodes('enrollment_type'); 
            $sel_rg = AssortmentInventories::getStoreCodes('region_code'); 
            $sel_st = AssortmentInventories::getStoreCodes('store_id');

            $divisions = AssortmentItemInventories::getDivisionList();
            $sel_dv = AssortmentItemInventories::getItemCodes('division');
            $sel_cat = AssortmentItemInventories::getItemCodes('category');
            $sel_scat = AssortmentItemInventories::getItemCodes('sub_category');
            $sel_br = AssortmentItemInventories::getItemCodes('brand');
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
            'divisions', 'sel_dv', 'sel_cat', 'sel_scat', 'sel_br' ,'items', 'header','type'));
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
            'divisions', 'sel_dv', 'sel_cat', 'sel_scat', 'sel_br' ,'items', 'header','type'));
        }

        set_time_limit(0);
        if ($request->has('download')) {

            $take = 1000; // adjust this however you choose
            $skip = 0; // used to skip over the ones you've already processed

            $writer = WriterFactory::create(Type::XLSX);
            $writer->setShouldCreateNewSheetsAutomatically(true); // default value
            $writer->openToBrowser($header.'.xlsx');
            $writer->addRow(array('STORE CODE', 'STORE NAME', 'OTHER CODE', 'SKU CODE', 'ITEM DESCRIPTION', 'IG', 'FSO MULTIPLIER', 'SAPC',
                        'WHPC', 'WHCS', 'SO', 'FSO', 'FSO VAL', 'OSA', 'OSS', 'TRANSACTION DATE', 'POSTING DATE AND TIME', 'SIGNATURE LINK'));

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
                        $row_data[0] = $row->store_code;
                        $row_data[1] = $row->store_name;
                        $row_data[2] = $row->other_barcode;
                        $row_data[3] = $row->sku_code;
                        $row_data[4] = $row->description;
                        $row_data[5] = $row->ig;
                        $row_data[6] = $row->fso_multiplier;
                        $row_data[7] = $row->sapc;
                        $row_data[8] = $row->whpc;
                        $row_data[9] = $row->whcs;
                        $row_data[10] = $row->so;
                        $row_data[11] = $row->fso;
                        $row_data[12] = (double)$row->fso_val;
                        $row_data[13] = $row->osa;
                        $row_data[14] = $row->oos;
                        $row_data[15] = $row->transaction_date;
                        $row_data[16] = $row->created_at;
                        $row_data[17] = $link;
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
                        $row_data[0] = $row->store_code;
                        $row_data[1] = $row->store_name;
                        $row_data[2] = $row->other_barcode;
                        $row_data[3] = $row->sku_code;
                        $row_data[4] = $row->description;
                        $row_data[5] = $row->ig;
                        $row_data[6] = $row->fso_multiplier;
                        $row_data[7] = $row->sapc;
                        $row_data[8] = $row->whpc;
                        $row_data[9] = $row->whcs;
                        $row_data[10] = $row->so;
                        $row_data[11] = $row->fso;
                        $row_data[12] = (double)$row->fso_val;
                        $row_data[13] = $row->osa;
                        $row_data[14] = $row->oos;
                        $row_data[15] = $row->transaction_date;
                        $row_data[16] = $row->created_at;
                        $row_data[17] = $link;
                        $plunck_data[] = $row_data;
                    }

                    $writer->addRows($plunck_data); // add multiple rows at a time
                    
                }
            }
            

            $writer->close();


            // \Excel::create($header, function($excel)  use ($items,$report_type){
            //     $excel->sheet('Sheet1', function($sheet) use ($items,$report_type) {
            //         $sheet->row(1, array('STORE CODE', 'STORE NAME', 'OTHER CODE', 'SKU CODE', 'ITEM DESCRIPTION', 'IG', 'FSO MULTIPLIER', 'SAPC',
            //             'WHPC', 'WHCS', 'SO', 'FSO', 'FSO VAL', 'OSA', 'OSS', 'TRANSACTION DATE', 'POSTING DATE AND TIME', 'SIGNATURE LINK'));
            //         $row = 2;
            //         foreach ($items as $item) {
            //             if(!is_null($item->signature)){
            //                 if($report_type == 2){
            //                     $link = url('api/pcountimage', [$item->signature]);
            //                 }else{
            //                     $link = url('api/assortmentimage', [$item->signature]);
            //                 }
                            
            //             }else{
            //                 $link = '';
            //             }
            //             // dd($item);

            //             $sheet->setCellValueByColumnAndRow(0,$row, $item->store_code);
            //             $sheet->setCellValueByColumnAndRow(1,$row, $item->store_name);
            //             $sheet->setCellValueByColumnAndRow(2,$row, $item->other_barcode);
            //             $sheet->setCellValueByColumnAndRow(3,$row, $item->sku_code);
            //             $sheet->setCellValueByColumnAndRow(4,$row, $item->description);
            //             $sheet->setCellValueByColumnAndRow(5,$row, $item->ig);
            //             $sheet->setCellValueByColumnAndRow(6,$row, $item->fso_multiplier);
            //             $sheet->setCellValueByColumnAndRow(7,$row, $item->sapc);
            //             $sheet->setCellValueByColumnAndRow(8,$row, $item->whpc);
            //             $sheet->setCellValueByColumnAndRow(9,$row, $item->whcs);
            //             $sheet->setCellValueByColumnAndRow(10,$row, $item->so);
            //             $sheet->setCellValueByColumnAndRow(11,$row, $item->fso);
            //             $sheet->setCellValueByColumnAndRow(12,$row, $item->fso_val);
            //             $sheet->setCellValueByColumnAndRow(13,$row, $item->osa);
            //             $sheet->setCellValueByColumnAndRow(14,$row, $item->oos);
            //             $sheet->setCellValueByColumnAndRow(15,$row, $item->transaction_date);
            //             $sheet->setCellValueByColumnAndRow(16,$row, $item->created_at);
            //             $sheet->setCellValueByColumnAndRow(17,$row, $link);
                        

            //             $sheet->setCellValue('R'.$row, $link);
            //             $sheet->getCell('R'.$row)->getHyperlink()->setUrl($link);
            //             $sheet->getCell('R'.$row)->getHyperlink()->setTooltip('Download Signature');
            //             $sheet->getStyle('R'.$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            //             $row++;
            //         }

            //     });
            // })->download('xlsx');
        }
    }

}
