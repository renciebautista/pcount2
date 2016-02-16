<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\StoreInventories;
use App\Models\ItemInventories;

class OutofstockController extends Controller
{
    public function sku(){
        $frm = date("m-d-Y");
        $to = date("m-d-Y");
        $areas = StoreInventories::getAreaList();
        $sel_ar = StoreInventories::getStoreCodes('area');
        $sel_st = StoreInventories::getStoreCodes('store_id');
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

        $inventories = ItemInventories::getOosPerStore($data);
        return view('oos.sku', compact('inventories','frm', 'to', 'areas', 'sel_ar', 'sel_st'));
    }

    public function postsku(Request $request){
        $sel_ar = $request->ar;
        $sel_st = $request->st;
        $frm = $request->fr;
        $to = $request->to;

        $areas = StoreInventories::getAreaList();
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
        $inventories = ItemInventories::getOosPerStore($data);

        if ($request->has('submit')) {
            return view('oos.sku', compact('inventories','frm', 'to', 'areas', 'sel_ar', 'sel_st'));
        }

        if ($request->has('download')) {
            \Excel::create('OOS SKU', function($excel)  use ($inventories){

                $weeks = [];
                $items = [];
                $sku = [];
                foreach ($inventories as $value) {
                    $week_start = new \DateTime();
                    $week_start->setISODate($value->yr,$value->yr_week,1);
                    // $weeks[$week_start->format('Y-m-d')] = "Week ".$value->yr_week." of ".$value->yr;
                    $weeks[$week_start->format('Y-m-d')] = "Week ".$value->yr_week." of ".$value->yr;
                    $items[$value->area][$value->store_name][$value->sku_code]["Week ".$value->yr_week." of ".$value->yr] = $value->oos;
                    $sku[$value->sku_code] = $value->description;

                }
                
                ksort($weeks);
                // dd($weeks);
                $excel->sheet('Sheet1', function($sheet) use ($weeks,$items,$sku) {
                    $col = 3;
                    $row = 2;
                    foreach ($weeks as $week) {
                        $sheet->setCellValueByColumnAndRow($col,$row, $week);
                        $col_array[$week] = $col;
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
                                    $week_col = $col_array[$k];
                                    if($oos){
                                        $sheet->setCellValueByColumnAndRow($week_col,$row, $oos);
                                    }
                                }
                                $item_wk_total = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex(3).$row.":".\PHPExcel_Cell::stringFromColumnIndex($last_col-1).$row.")";

                                $sheet->setCellValueByColumnAndRow($last_col,$row, $item_wk_total);
                                $row++;
                            }
                        }
                        $sheet->setCellValueByColumnAndRow(0,$row, $area. " Total");
                        $last_row = $row -1;
                        foreach ($weeks as $week) {
                            $week_col = $col_array[$week];
                            $area_week_total = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($week_col).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($week_col).$last_row.")";
                            $sheet->setCellValueByColumnAndRow($week_col,$row, $area_week_total);
                            $per_area_total[$week][$area] = \PHPExcel_Cell::stringFromColumnIndex($week_col).$row;

                        }
                        $area_grand_total = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($last_col).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($last_col).$last_row.")";
                        $sheet->setCellValueByColumnAndRow($last_col,$row, $area_grand_total);
                        $g_total[] = \PHPExcel_Cell::stringFromColumnIndex($last_col).$row;
                        $row++;
                        $start_row = $row;
                    }
                    $sheet->setCellValueByColumnAndRow(0,$row, 'Grand Total');

                    $col = 3;
                    foreach ($weeks as $week) {
                        $area_total = [];
                        $wek_cols = $per_area_total[$week];
                        foreach ($wek_cols as $cell) {
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
