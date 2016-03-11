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
        $frm = date("m-d-Y");
        $to = date("m-d-Y");
        $report_type = 1;
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

        if($report_type == 2){
            $header = 'MKL OOS SKU Report';
            $inventories = ItemInventories::getOosPerStore($data);
        }else{
            $header = 'Assortment OOS SKU Report';
            $inventories = AssortmentItemInventories::getOosPerStore($data);
        }

        return view('oos.sku', compact('inventories','frm', 'to', 'areas', 'sel_ar', 'sel_st', 'header', 'type'));
    }

    public function postsku(Request $request,$type = null){
        $sel_ar = $request->ar;
        $sel_st = $request->st;
        $frm = $request->fr;
        $to = $request->to;

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
        
        if($report_type == 2){
            $header = 'MKL OOS SKU Report';
            $inventories = ItemInventories::getOosPerStore($data);
        }else{
            $header = 'Assortment OOS SKU Report';
            $inventories = AssortmentItemInventories::getOosPerStore($data);
        }


        if ($request->has('submit')) {
            return view('oos.sku', compact('inventories','frm', 'to', 'areas', 'sel_ar', 'sel_st', 'header', 'type'));
        }

        if ($request->has('download')) {
            \Excel::create($header, function($excel)  use ($data,$inventories){

                $items = [];
                $sku = [];
                foreach ($inventories as $value) {
                    $week_start = new \DateTime();
                    $week_start->setISODate($value->yr,$value->yr_week,1);
                    $items[$value->area][$value->store_name][$value->sku_code][$value->transaction_date] = $value->oos;
                    $sku[$value->sku_code] = $value->description;

                }
                
                // ksort($weeks);
                // dd($weeks);
                $excel->sheet('Sheet1', function($sheet) use ($data,$items,$sku) {
                    $col = 3;
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
                    $sheet->setCellValueByColumnAndRow(2,$row , 'ITEM DESCRIPTION');
                    
                    $row = 3;
                    $start_row = $row;
                    foreach ($items as $area => $area_value) {
                        $sheet->setCellValueByColumnAndRow(0,$row, $area );
                        foreach ($area_value as $store => $store_value) {
                            $sheet->setCellValueByColumnAndRow(1,$row, $store );
                            foreach ($store_value as $item => $item_value) {
                                $sheet->setCellValueByColumnAndRow(2,$row, $sku[$item] );
                                foreach ($item_value as $k => $oos) {
                                    $day_col = $col_array[$k];
                                    if($oos){
                                        $sheet->setCellValueByColumnAndRow($day_col,$row, $oos);
                                    }
                                }
                                $store_item_oos_total = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex(3).$row.":".\PHPExcel_Cell::stringFromColumnIndex($last_col-1).$row.")";

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

                    $col = 3;
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
