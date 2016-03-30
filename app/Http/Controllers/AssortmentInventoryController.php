<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\AssortmentItemInventories;
use App\Models\AssortmentInventories;

class AssortmentInventoryController extends Controller
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

        $items = AssortmentItemInventories::filter($data);

        return view('inventory.assortment',compact('frm', 'to', 'agencies','sel_ag',
            'sel_cl', 'sel_ch', 'sel_ds', 'sel_en', 'sel_rg', 'sel_st',
            'divisions', 'sel_dv', 'sel_cat', 'sel_scat', 'sel_br' ,'items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $agencies = AssortmentInventories::getAgencyList();

        $sel_ag = $request->ag;
        $sel_cl = $request->cl;
        $sel_ch = $request->ch;
        $sel_ds = $request->ds;
        $sel_en = $request->en;
        $sel_rg = $request->rg;
        $sel_st = $request->st;

        $divisions = AssortmentItemInventories::getDivisionList();
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


        $items = AssortmentItemInventories::filter($data);

        if ($request->has('submit')) {
            return view('inventory.assortment',compact('frm', 'to', 'agencies','sel_ag',
            'sel_cl', 'sel_ch', 'sel_ds', 'sel_en', 'sel_rg', 'sel_st',
            'divisions', 'sel_dv', 'sel_cat', 'sel_scat', 'sel_br' ,'items'));
        }

        if ($request->has('download')) {
            \Excel::create('Assortment Posted Transaction', function($excel)  use ($items){
                $excel->sheet('Sheet1', function($sheet) use ($items) {
                    $sheet->row(1, array('STORE CODE', 'STORE NAME', 'OTHER CODE', 'SKU CODE', 'ITEM DESCRIPTION', 'IG', 'FSO MULTIPLIER', 'SAPC',
                        'WHPC', 'WHCS', 'SO', 'FSO', 'FSO VAL', 'OSA', 'OSS', 'TRANSACTION DATE', 'POSTING DATE AND TIME', 'SIGNATURE LINK'));
                    $row = 2;
                    foreach ($items as $item) {
                        if(!is_null($item->signature)){
                            $link = url('api/assortmentimage', [$item->signature]);
                        }else{
                            $link = '';
                        }

                        $sheet->setCellValueByColumnAndRow(0,$row, $item->store_code);
                        $sheet->setCellValueByColumnAndRow(1,$row, $item->store_name);
                        $sheet->setCellValueByColumnAndRow(2,$row, $item->other_barcode);
                        $sheet->setCellValueByColumnAndRow(3,$row, $item->sku_code);
                        $sheet->setCellValueByColumnAndRow(4,$row, $item->description);
                        $sheet->setCellValueByColumnAndRow(5,$row, $item->ig);
                        $sheet->setCellValueByColumnAndRow(6,$row, $item->fso_multiplier);
                        $sheet->setCellValueByColumnAndRow(7,$row, $item->sapc);
                        $sheet->setCellValueByColumnAndRow(8,$row, $item->whpc);
                        $sheet->setCellValueByColumnAndRow(9,$row, $item->whcs);
                        $sheet->setCellValueByColumnAndRow(10,$row, $item->so);
                        $sheet->setCellValueByColumnAndRow(11,$row, $item->fso);
                        $sheet->setCellValueByColumnAndRow(12,$row, $item->fso_val);
                        $sheet->setCellValueByColumnAndRow(13,$row, $item->osa);
                        $sheet->setCellValueByColumnAndRow(14,$row, $item->oos);
                        $sheet->setCellValueByColumnAndRow(15,$row, $item->transaction_date);
                        $sheet->setCellValueByColumnAndRow(16,$row, $item->created_at);
                        $sheet->setCellValueByColumnAndRow(17,$row, $link);
                        

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
