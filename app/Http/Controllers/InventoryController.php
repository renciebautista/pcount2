<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\ItemInventories;
use App\Models\StoreInventories;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $frm = date("m-d-Y");
        $to = date("m-d-Y");    

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

        $items = ItemInventories::filter($data);

        return view('inventory.index',compact('frm', 'to', 'agencies','sel_ag',
            'sel_cl', 'sel_ch', 'sel_ds', 'sel_en', 'sel_rg', 'sel_st',
            'divisions', 'sel_dv', 'sel_cat', 'sel_scat', 'sel_br' ,'items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $agencies = StoreInventories::getAgencyList();

        $sel_ag = $request->ag;
        $sel_cl = $request->cl;
        $sel_ch = $request->ch;
        $sel_ds = $request->ds;
        $sel_en = $request->en;
        $sel_rg = $request->rg;
        $sel_st = $request->st;

        $divisions = ItemInventories::getDivisionList();
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


        $items = ItemInventories::filter($data);

        if ($request->has('submit')) {
            return view('inventory.index',compact('frm', 'to', 'agencies','sel_ag',
            'sel_cl', 'sel_ch', 'sel_ds', 'sel_en', 'sel_rg', 'sel_st',
            'divisions', 'sel_dv', 'sel_cat', 'sel_scat', 'sel_br' ,'items'));
        }

        if ($request->has('download')) {
            \Excel::create('Posted Transaction', function($excel)  use ($items){
                $excel->sheet('Sheet1', function($sheet) use ($items) {
                    $sheet->row(1, array('STORE CODE', 'STORE NAME', 'OTHER CODE', 'SKU CODE', 'ITEM DESCRIPTION', 'IG', 'FSO CONVERSION FACTOR', 'SAPC',
                        'WHPC', 'WHCS', 'SO', 'FSO', 'FSO VAL', 'TRANSACTION DATE', 'POSTING DATE AND TIME', 'SIGNATURE LINK'));
                    $row = 2;
                    foreach ($items as $item) {
                        if(!is_null($item->signature)){
                            // $link = '<a href=">' . base_path() . '/signature?name=' . $row->signature . '">' . $row->signature . '</a>';
                            $link = url('api/image', [$item->signature]);
                        }else{
                            $link = '';
                        }

                        $sheet->setCellValueByColumnAndRow(0,$row, $item->store_code);
                        $sheet->setCellValueByColumnAndRow(1,$row, $item->store_name);
                        $sheet->setCellValueByColumnAndRow(2,$row, $item->other_barcode);
                        $sheet->setCellValueByColumnAndRow(3,$row, $item->sku_code);
                        $sheet->setCellValueByColumnAndRow(4,$row, $item->description);
                        $sheet->setCellValueByColumnAndRow(5,$row, $item->ig);
                        $sheet->setCellValueByColumnAndRow(6,$row, $item->conversion);
                        $sheet->setCellValueByColumnAndRow(7,$row, $item->sapc);
                        $sheet->setCellValueByColumnAndRow(8,$row, $item->whpc);
                        $sheet->setCellValueByColumnAndRow(9,$row, $item->whcs);
                        $sheet->setCellValueByColumnAndRow(10,$row, $item->so);
                        $sheet->setCellValueByColumnAndRow(11,$row, $item->fso);
                        $sheet->setCellValueByColumnAndRow(12,$row, $item->fso_val);
                        $sheet->setCellValueByColumnAndRow(13,$row, $item->transaction_date);
                        $sheet->setCellValueByColumnAndRow(14,$row, $item->created_at);
                        $sheet->setCellValueByColumnAndRow(15,$row, $link);
                        

                        $sheet->setCellValue('P'.$row, $link);
                        $sheet->getCell('P'.$row)->getHyperlink()->setUrl($link);
                        $sheet->getCell('P'.$row)->getHyperlink()->setTooltip('Download Signature');
                       $sheet->getStyle('P'.$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                        $row++;
                    }

                });
            })->download('xlsx');
        }
    }

}
