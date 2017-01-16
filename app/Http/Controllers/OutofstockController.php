<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\StoreInventories;
use App\Models\ItemInventories;
use App\Models\AssortmentInventories;
use App\Models\AssortmentItemInventories;

class OutofstockController extends Controller
{
    public function sku($type = null){

        ini_set('max_input_vars', 3000);
        set_time_limit(0);

        $frm = date("m-d-Y");
        $to = date("m-d-Y");
        $report_type = 1;
        $sel_av = [];
         $sel_tag = [];
        $availability =['1'=>'oos','2'=>'osa'];
         $tags = ['1' => 'OSA', '2' => 'NPI'];
        if((is_null($type)) || ($type != 'assortment')){
            $report_type = 2;
        }
        if($report_type == 2){
            $areas = StoreInventories::getAreaList();
            $sel_ar = StoreInventories::getStoreCodes('area');
            $sel_st = StoreInventories::getStoreCodes('store_id');
        }else{
            $areas = AssortmentInventories::getAreaList();
            $sel_ar = AssortmentInventories::getStoreCodes('area');
            $sel_st = AssortmentInventories::getStoreCodes('store_id');
        }

        if(!empty($sel_ar)){
            $data['areas'] = $sel_ar;
        }
        if(!empty($sel_st)){
            $data['store'] = $sel_st;
        }
        if(!empty($frm)){
            $data['from'] = $frm;
        }
        if(!empty($to)){
            $data['to'] = $to;
        }
        if(!empty($sel_av)){
            $data['availability'] = $sel_av;
        }
        if(!empty($sel_tag)){
            $data['tags'] = $sel_tag;
        }
      
        if($report_type == 2){
            $header = 'MKL OOS SKU Report';
            $inventories = ItemInventories::getOosPerStore($data);
        }else{
            $header = 'Assortment OOS SKU Report';
            $inventories = AssortmentItemInventories::getOosPerStore($data);
        }

        return view('oos.sku', compact('inventories','frm', 'to', 'areas', 'sel_ar', 'sel_st', 'header', 'type','availability','sel_av','sel_tag','tags'));
    }

    public function postsku(Request $request,$type = null){

        ini_set('max_input_vars', 3000);
        set_time_limit(0);
        $sel_ar = $request->ar;
        $sel_st = $request->st;
        $frm = $request->fr;
        $to = $request->to;
        $sel_av = $request->availability;
        $sel_tag = $request->tags;
        $availability =['1'=>'oos','2'=>'osa'];
        $tags = ['1' => 'OSA', '2' => 'NPI'];
        $report_type = 1;
        if((is_null($type)) || ($type != 'assortment')){
            $report_type = 2;
        }
        if($report_type == 2){
            $areas = StoreInventories::getAreaList();
        }else{
            $areas = AssortmentInventories::getAreaList();
        }


        if(!empty($sel_ar)){
            $data['areas'] = $sel_ar;
        }

        if(!empty($sel_st)){
            $data['stores'] = $sel_st;
        }

        if(!empty($frm)){
            $data['from'] = $frm;
        }
        if(!empty($to)){
            $data['to'] = $to;
        }
        if(!empty($sel_av)){
            $data['availability'] = $sel_av;
        }
         if(!empty($sel_tag)){
            $data['tags'] = $sel_tag;
        }
        
        if($report_type == 2){
            $header = 'MKL OOS SKU Report';
            $inventories = ItemInventories::getOosPerStore($data);

        }else{
            $header = 'Assortment OOS SKU Report';
            $inventories = AssortmentItemInventories::getOosPerStore($data);
        }


        if ($request->has('submit')) {

            return view('oos.sku', compact('inventories','frm', 'to', 'areas', 'sel_ar', 'sel_st', 'header', 'type' , 'type','sel_av','availability','sel_tag','tags'));
        }

        if ($request->has('download')) {

            \Excel::create($header, function($excel)  use ($data,$inventories){

                $items = [];
                $sku = [];
                $stores = [];

                foreach ($inventories as $value) {
                    $week_start = new \DateTime();
                    $week_start->setISODate($value->yr,$value->yr_week,1);
                    $items[$value->area][$value->store_name][$value->sku_code]['other'] = $value->other_barcode;
                    $items[$value->area][$value->store_name][$value->sku_code]['oos'][$value->transaction_date] = $value->oos;
                    $sku[$value->sku_code] = $value->description;
                    $stores[$value->store_name] = ['store_name' => $value->store_name, 'store_code' => $value->store_code, 'channel' => $value->channel_name];

                }
                $excel->sheet('Sheet1', function($sheet) use ($data,$items,$sku,$stores) {
                    $col = 7;
                    $row = 2;
                    $dates = ItemInventories::getDays($data['from'],$data['to']);
                    foreach ($dates as $date) {
                        $sheet->setCellValueByColumnAndRow($col,$row, $date->date);
                        $col_array[$date->date] = $col;
                        $col++;
                    }
                    $last_col = $col;
                    $sheet->setCellValueByColumnAndRow($col,$row , 'Grand Total');


                    $sheet->setCellValueByColumnAndRow(0,$row , 'AREA');
                    $sheet->setCellValueByColumnAndRow(1,$row , 'STORE NAME');
                    $sheet->setCellValueByColumnAndRow(2,$row , 'STORE CODE');
                    $sheet->setCellValueByColumnAndRow(3,$row , 'CHANNEL NAME');
                    $sheet->setCellValueByColumnAndRow(4,$row , 'SKU CODE');
                    $sheet->setCellValueByColumnAndRow(5,$row , 'OTHER CODE');
                    $sheet->setCellValueByColumnAndRow(6,$row , 'ITEM DESCRIPTION');
                    // dd($items);
                    $row = 3;
                    $start_row = $row;
                    foreach ($items as $area => $area_value) {
                        foreach ($area_value as $store => $store_value) {
                            foreach ($store_value as $item => $item_value) {
                                $sheet->setCellValueByColumnAndRow(0,$row, $area );
                                $sheet->setCellValueByColumnAndRow(1,$row, $store );
                                $sheet->setCellValueByColumnAndRow(2,$row, $stores[$store]['store_code'] );
                                $sheet->setCellValueByColumnAndRow(3,$row, $stores[$store]['channel'] );
                                $sheet->setCellValueByColumnAndRow(4,$row, $item);
                                $sheet->setCellValueByColumnAndRow(5,$row, $item_value['other']);
                                $sheet->setCellValueByColumnAndRow(6,$row, $sku[$item] );
                                foreach ($item_value['oos'] as $k => $oos) {
                                    $day_col = $col_array[$k];
                                    $sheet->setCellValueByColumnAndRow($day_col,$row, $oos);
                                }
                                $store_item_oos_total = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex(7).$row.":".\PHPExcel_Cell::stringFromColumnIndex($last_col-1).$row.")";

                                $sheet->setCellValueByColumnAndRow($last_col,$row, $store_item_oos_total);
                                $row++;
                            }
                        }
                        $sheet->setCellValueByColumnAndRow(0,$row, $area. " Total");
                        $last_row = $row -1;
                        foreach ($dates as $date) {
                            $day_col = $col_array[$date->date];
                            $area_week_total = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($day_col).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($day_col).$last_row.")";
                            $sheet->setCellValueByColumnAndRow($day_col,$row, $area_week_total);
                            $per_area_total[$date->date][$area] = \PHPExcel_Cell::stringFromColumnIndex($day_col).$row;

                        }
                        $area_grand_total = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($last_col).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($last_col).$last_row.")";
                        $sheet->setCellValueByColumnAndRow($last_col,$row, $area_grand_total);
                        $g_total[] = \PHPExcel_Cell::stringFromColumnIndex($last_col).$row;
                        $row++;
                        $start_row = $row;
                    }
                    $sheet->setCellValueByColumnAndRow(0,$row, 'Grand Total');

                    $col = 7;
                    foreach ($dates as $date) {
                        $area_total = [];
                        $day_cols = $per_area_total[$date->date];
                        foreach ($day_cols as $cell) {
                            $area_total[] = $cell;
                        }
                        $sheet->setCellValueByColumnAndRow($col,$row, '=sum('.implode(",", $area_total).')');
                        $col++;
                    }

                    $sheet->setCellValueByColumnAndRow($last_col,$row, '=sum('.implode(",", $g_total).')');


                });
            })->download('xlsx');
        }
    }
}
