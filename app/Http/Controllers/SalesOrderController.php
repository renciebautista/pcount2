<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

use App\Models\ItemInventories;
use App\Models\StoreInventories;
use App\Models\AssortmentInventories;
use App\Models\AssortmentItemInventories;

class SalesOrderController extends Controller
{
    public function area($type = null){
        $frm = date("m-d-Y");
        $to = date("m-d-Y");
        $sel_av = [];
        $sel_tag = [];
        $sel_reg = [];
        $availability =['1'=>'oos','2'=>'osa'];
        $tags = ['1' => 'OSA', '2' => 'NPI'];
        $report_type = 1;
        if((is_null($type)) || ($type != 'assortment')){
            $report_type = 2;
        }
        if($report_type == 2){
            $areas = StoreInventories::getAreaList();
            $sel_ar = StoreInventories::getStoreCodes('area');
            $regions = StoreInventories::getRegionList();
        }else{
            $areas = AssortmentInventories::getAreaList();
            $sel_ar = AssortmentInventories::getStoreCodes('area');
            $regions = AssortmentInventories::getRegionList();
        }
        
        if(!empty($sel_br)){
            $data['areas'] = $sel_ar;
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
        if(!empty($sel_reg)){
            $data['regions'] = $sel_reg;
        }


        if($report_type == 2){
            $header = 'MKL SO Per Area Report';
            $inventories = ItemInventories::getSoPerArea($data);
        }else{
            $header = 'Assortment SO Per Area Report';
            $inventories = AssortmentItemInventories::getSoPerArea($data);
        }

        
        return view('so.area', compact('inventories','frm', 'to', 'areas', 'sel_ar', 'header' , 'type','availability','sel_av','sel_tag','tags','sel_reg','regions'));
    }

    public function postArea(Request $request,$type = null){

        $sel_ar = $request->ar;
        $sel_reg = $request->reg;
        $sel_tag = $request->tags;
        $frm = $request->fr;
        $to = $request->to;

        $sel_av = $request->availability;
        $availability =['1'=>'oos','2'=>'osa'];
        $tags = ['1' => 'OSA', '2' => 'NPI'];
        $report_type = 1;
        if((is_null($type)) || ($type != 'assortment')){
            $report_type = 2;
        }

        if($report_type == 2){
            $areas = StoreInventories::getAreaList();
            $regions = StoreInventories::getRegionList();
        }else{
            $areas = AssortmentInventories::getAreaList();
             $regions = AssortmentInventories::getRegionList();
        }

        if(!empty($sel_ar)){
            $data['areas'] = $sel_ar;
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
         if(!empty($sel_reg)){
            $data['regions'] = $sel_reg;
        }
        if($report_type == 2){
            $header = 'MKL SO Per Area Report';
            $inventories = ItemInventories::getSoPerArea($data);
        }else{
            $header = 'Assortment SO Per Area Report';
            $inventories = AssortmentItemInventories::getSoPerArea($data);
        }

        if ($request->has('submit')) {

            return view('so.area', compact('inventories','frm', 'to', 'areas', 'sel_ar','header' , 'type','sel_av','availability','tags','sel_tag','sel_reg','regions'));
        }
        
        if ($request->has('download')) {
            \Excel::create($header, function($excel)  use ($inventories,$report_type){
                $weeks = [];
                $items = [];
                foreach ($inventories as $value) {
                    $weeks[$value->yr_week.$value->yr] = "Week ".$value->yr_week." of ".$value->yr;
                    $items[$value->area]["Week ".$value->yr_week." of ".$value->yr] = ['fso' => $value->fso_sum, 'fso_val' => $value->fso_val_sum];
                }

                $excel->sheet('Sheet1', function($sheet) use ($items,$weeks,$report_type) {
                    $col_array =[];
                    $col = 1;
                    foreach ($weeks as $week) {
                        $sheet->setCellValueByColumnAndRow($col,2, $week);
                        $n_col = $col+1;
                        $col_array[$week] = $col;
                        $sheet->mergeCells(\PHPExcel_Cell::stringFromColumnIndex($col)."2:".\PHPExcel_Cell::stringFromColumnIndex($n_col)."2");
                        $col = $col +2;
                    }
                    $fso_sub_total_col = $col;
                    $fso_val_sub_total_col = $col+1;
                    $sheet->setCellValueByColumnAndRow($fso_sub_total_col,2, 'Total Sum of FSO');
                    $sheet->setCellValueByColumnAndRow($fso_val_sub_total_col,2, 'Total Sum of FSO VAL');

                    $col = 0;
                    $sheet->setCellValueByColumnAndRow($col,3, 'AREA');
                    foreach ($weeks as $week) {
                        $sheet->setCellValueByColumnAndRow($col+1,3, 'Sum of FSO');
                        $sheet->setCellValueByColumnAndRow($col+2,3, 'Sum of FSO VAL');
                        $col = $col +2;
                    }
                   
                    $total_col = $col;

                    $row = 4;
                    $col = 1;
                    $total_row = count($items)+4;
                    $last_row = $total_row -1;
                    $sheet->setCellValueByColumnAndRow(0,$total_row, 'Grand Total');

                    $fso_subtotal = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($fso_sub_total_col)."4:".\PHPExcel_Cell::stringFromColumnIndex($fso_sub_total_col).$last_row.")";
                    $fso_val_subtotal = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($fso_val_sub_total_col)."4:".\PHPExcel_Cell::stringFromColumnIndex($fso_val_sub_total_col).$last_row.")";                            
                    $sheet->setCellValueByColumnAndRow($fso_sub_total_col,$total_row,  $fso_subtotal);
                    $sheet->setCellValueByColumnAndRow($fso_val_sub_total_col,$total_row, $fso_val_subtotal);

                    foreach ($items as $key => $value) {
                        $fso_ar = [];
                        $fsoval_ar =[];
                        $sheet->setCellValueByColumnAndRow(0,$row, $key);
                        foreach ($value as $k => $rowValue) {
                            $fso_col = $col_array[$k];
                            $fso_val_col = $col_array[$k]+1;
                            $fso_total = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($fso_col)."4:".\PHPExcel_Cell::stringFromColumnIndex($fso_col).$last_row.")";
                            $fso_val_total = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($fso_col+1)."4:".\PHPExcel_Cell::stringFromColumnIndex($fso_col+1).$last_row.")";                            

                            $fso_ar[] = \PHPExcel_Cell::stringFromColumnIndex($fso_col).$row;
                            $fsoval_ar[] = \PHPExcel_Cell::stringFromColumnIndex($fso_val_col).$row;

                            $sheet->setCellValueByColumnAndRow($fso_col,$row, $rowValue['fso']);
                            $sheet->setCellValueByColumnAndRow($fso_col,$total_row, $fso_total);
                            $sheet->setCellValueByColumnAndRow($fso_val_col,$row, $rowValue['fso_val']);
                            $sheet->setCellValueByColumnAndRow($fso_val_col,$total_row, $fso_val_total);
                        }
                        $sheet->setCellValueByColumnAndRow($total_col+1,$row, '=sum('.implode(",", $fso_ar).')');
                        $sheet->setCellValueByColumnAndRow($total_col+2,$row, '=sum('.implode(",", $fsoval_ar).')');
                        $row++;
                    }
                });
            })->download('xlsx');
        }
    }

    public function store($type = null){
        $frm = date("m-d-Y");
        $to = date("m-d-Y");
        $report_type = 1;
        $sel_av = [];
        $sel_tag = [];
        $sel_reg = [];
        $tags = ['1' => 'OSA', '2' => 'NPI'];
        $availability =['1'=>'oos','2'=>'osa'];
        if((is_null($type)) || ($type != 'assortment')){
            $report_type = 2;
        }
        if($report_type == 2){
            $areas = StoreInventories::getAreaList();
            $sel_ar = StoreInventories::getStoreCodes('area');
            $sel_st = StoreInventories::getStoreCodes('store_id');
            $regions = StoreInventories::getRegionList();
        }else{
            $areas = AssortmentInventories::getAreaList();
            $sel_ar = AssortmentInventories::getStoreCodes('area');
            $sel_st = AssortmentInventories::getStoreCodes('store_id');
            $regions = AssortmentInventories::getRegionList();
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
        if(!empty($sel_reg)){
            $data['regions'] = $sel_reg;
        }

        if($report_type == 2){
            $header = 'MKL SO Per Store Report';
            $inventories = ItemInventories::getSoPerStores($data);
        }else{
            $header = 'Assortment SO Per Store Report';
            $inventories = AssortmentItemInventories::getSoPerStores($data);
        }

        
        return view('so.store', compact('inventories','frm', 'to', 'areas', 'sel_ar', 'sel_st', 'header', 'type','availability','sel_av','sel_tag','tags','sel_reg','regions'));
    }

    public function postStore(Request $request,$type = null){
        // dd($request->all());
        $sel_ar = $request->ar;
         $sel_reg = $request->reg;
        $sel_st = $request->st;
        $frm = $request->fr;
        $to = $request->to;
        $sel_av = $request->availability;
        $sel_tag =$request->tags;

        $availability =['1'=>'oos','2'=>'osa'];
        $tags = ['1' => 'OSA', '2' => 'NPI'];


        $report_type = 1;
        if((is_null($type)) || ($type != 'assortment')){
            $report_type = 2;
        }
        if($report_type == 2){
            $areas = StoreInventories::getAreaList();
            $regions = StoreInventories::getRegionList();
        }else{
            $areas = AssortmentInventories::getAreaList();
            $regions = AssortmentInventories::getRegionList();
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
         if(!empty($sel_reg)){
            $data['regions'] = $sel_reg;
        }
        if($report_type == 2){
            $header = 'MKL SO Per Store Report';
            $inventories = ItemInventories::getSoPerStores($data);
        }else {
            $header = 'Assortment SO Per Store Report';
            $inventories = AssortmentItemInventories::getSoPerStores($data);
        }

        if ($request->has('submit')) {
            
            return view('so.store', compact('inventories','frm', 'to', 'areas', 'sel_ar', 'sel_st','header', 'type','availability','sel_av','sel_tag','tags','sel_reg','regions'));
        }
        
        if ($request->has('download')) {
            \Excel::create($header, function($excel)  use ($inventories){
                $weeks = [];
                $items = [];
                foreach ($inventories as $value) {
                    $week_start = new \DateTime();
                    $week_start->setISODate($value->yr,$value->yr_week);
                    // $weeks[$week_start->getTimestamp()] = "Week ".$value->yr_week." of ".$value->yr;
                    $weeks[$week_start->format('Y-m-d')] = "Week ".$value->yr_week." of ".$value->yr;
                    $items[$value->area][$value->store_name]["Week ".$value->yr_week." of ".$value->yr] = ['fso' => $value->fso_sum, 'fso_val' => $value->fso_val_sum];


                }
                // dd($items);
                ksort($weeks);
                $excel->sheet('Sheet1', function($sheet) use ($items,$weeks) {
                    $col_array =[];
                    $col = 2;
                    foreach ($weeks as $week) {
                        $sheet->setCellValueByColumnAndRow($col,2, $week);
                        $n_col = $col+1;
                        $col_array[$week] = $col;
                        $sheet->mergeCells(\PHPExcel_Cell::stringFromColumnIndex($col)."2:".\PHPExcel_Cell::stringFromColumnIndex($n_col)."2");
                        $col = $col +2;
                    }
                    $fso_sub_total_col = $col;
                    $fso_val_sub_total_col = $col+1;
                    $sheet->setCellValueByColumnAndRow($fso_sub_total_col,2, 'Total Sum of FSO');
                    $sheet->setCellValueByColumnAndRow($fso_val_sub_total_col,2, 'Total Sum of FSO VAL');



                    $area_col = 0;
                    $store_col = 1;
                    $sheet->setCellValueByColumnAndRow($area_col,3, 'AREA');
                    $sheet->setCellValueByColumnAndRow($store_col,3, 'STORE NAME');
                    foreach ($weeks as $week) {
                        $sheet->setCellValueByColumnAndRow($store_col+1,3, 'Sum of FSO');
                        $sheet->setCellValueByColumnAndRow($store_col+2,3, 'Sum of FSO VAL');
                        $store_col = $store_col +2;
                    }

                    $row = 4;
                
                    $col_g_total = [];

                    $g_fso = [];
                    $g_fso_val = [];

                    foreach ($items as $key => $value) {
                        $first = true;
                        $total_row = count($value)+$row;
                        $last_row = $total_row - 1;
                        $start_row = $row;
                        foreach ($value as $skey => $record) {

                            if($first){
                                $sheet->setCellValueByColumnAndRow(0,$row, $key );
                                $first = false;
                            }
                            $sheet->setCellValueByColumnAndRow(1,$row, $skey);
                            $fso_ar = [];
                            $fsoval_ar = [];
                            foreach ($record as $k => $rowValue) {
                                $fso_col = $col_array[$k];
                                $fso_val_col = $col_array[$k]+1;
                                $fso_total = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($fso_col).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($fso_col).$last_row.")";
                                $fso_val_total = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($fso_col+1).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($fso_col+1).$last_row.")";                            

                                $fso_g_total[] = \PHPExcel_Cell::stringFromColumnIndex($fso_col).$total_row;
                                $fsoval_g_total[] = \PHPExcel_Cell::stringFromColumnIndex($fso_val_col).$total_row;

                                $sheet->setCellValueByColumnAndRow($fso_col,$row, $rowValue['fso']);
                                $sheet->setCellValueByColumnAndRow($fso_col,$total_row, $fso_total);
                                $sheet->setCellValueByColumnAndRow($fso_val_col,$row, $rowValue['fso_val']);
                                $sheet->setCellValueByColumnAndRow($fso_val_col,$total_row, $fso_val_total);

                                $col_gfso_total[$k][$key] = \PHPExcel_Cell::stringFromColumnIndex($fso_col).$total_row;
                                $col_gfsoval_total[$k][$key] = \PHPExcel_Cell::stringFromColumnIndex($fso_val_col).$total_row;

                                $fso_ar[] = \PHPExcel_Cell::stringFromColumnIndex($fso_col).$row;
                                $fsoval_ar[] = \PHPExcel_Cell::stringFromColumnIndex($fso_val_col).$row;

                            }

                            $sheet->setCellValueByColumnAndRow($fso_sub_total_col,$row, '=sum('.implode(",", $fso_ar).')');
                            $sheet->setCellValueByColumnAndRow($fso_val_sub_total_col,$row, '=sum('.implode(",", $fsoval_ar).')');
                            $row++;
                        }
                        
                        $sheet->setCellValueByColumnAndRow(0,$row, $key.' Total');

                        $fso_stotal = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($fso_sub_total_col).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($fso_sub_total_col).$last_row.")";
                        $fso_val_stotal = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($fso_val_sub_total_col).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($fso_val_sub_total_col).$last_row.")";

                        $sheet->setCellValueByColumnAndRow($fso_sub_total_col,$row, $fso_stotal);
                        $sheet->setCellValueByColumnAndRow($fso_val_sub_total_col,$row, $fso_val_stotal);

                        $g_fso[] = \PHPExcel_Cell::stringFromColumnIndex($fso_sub_total_col).$row;
                        $g_fso_val[] = \PHPExcel_Cell::stringFromColumnIndex($fso_val_sub_total_col).$row;

                        $row++;
                    }
                    $col = 2;
                    $sheet->setCellValueByColumnAndRow(0,$row, 'Grand Total');
                    
                    foreach ($weeks as $week) {
                        $fso_data = [];
                        $fsoval_data = [];
                        $fso_colums = $col_gfso_total[$week];
                        foreach ($fso_colums as $cell) {
                            $fso_data[] = $cell;
                        }
                        $fsoval_colums = $col_gfsoval_total[$week];
                        foreach ($fsoval_colums as $cell) {
                            $fsoval_data[] = $cell;
                        }
                        $sheet->setCellValueByColumnAndRow($col,$row, '=sum('.implode(",", $fso_data).')');
                        $sheet->setCellValueByColumnAndRow($col+1,$row, '=sum('.implode(",", $fsoval_data).')');


                        $col = $col + 2;
                    }

                    //grand total
                    $sheet->setCellValueByColumnAndRow($fso_sub_total_col,$row, '=sum('.implode(",", $g_fso).')');
                    $sheet->setCellValueByColumnAndRow($fso_val_sub_total_col,$row, '=sum('.implode(",", $g_fso_val).')');


                });
            })->download('xlsx');
        }
    }
}
