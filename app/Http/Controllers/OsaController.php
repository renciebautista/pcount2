<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\StoreInventories;
use App\Models\ItemInventories;

class OsaController extends Controller
{

    public function area()
    {
        $frm = date("m-d-Y");
        $to = date("m-d-Y");
        $areas = StoreInventories::getAreaList();
        $sel_ar = StoreInventories::getStoreCodes('area');
        if(!empty($sel_br)){
            $data['areas'] = $sel_ar;
        }
        if(!empty($frm)){
            $data['from'] = $frm;
        }
        if(!empty($to)){
            $data['to'] = $to;
        }

        $inventories = ItemInventories::getOsaPerArea($data);
        return view('osa.area', compact('inventories','frm', 'to', 'areas', 'sel_ar'));
    }

    public function postArea(Request $request){
        $sel_ar = $request->ar;

        $frm = $request->fr;
        $to = $request->to;

        $areas = StoreInventories::getAreaList();
        if(!empty($sel_ar)){
            $data['areas'] = $sel_ar;
        }
        if(!empty($frm)){
            $data['from'] = $frm;
        }
        if(!empty($to)){
            $data['to'] = $to;
        }
        $inventories = ItemInventories::getOsaPerArea($data);

        if ($request->has('submit')) {
            return view('osa.area', compact('inventories','frm', 'to', 'areas', 'sel_ar'));
        }
        
        if ($request->has('download')) {
            \Excel::create('OSA Per Area', function($excel)  use ($inventories){
                $weeks = [];
                $items = [];
                foreach ($inventories as $value) {
                    $weeks[$value->yr_week.$value->yr] = "Week ".$value->yr_week." of ".$value->yr;
                    $items[$value->area]["Week ".$value->yr_week." of ".$value->yr] = ['passed' => $value->passed, 'failed' => $value->failed];
                }
                // dd($items);
                $excel->sheet('Sheet1', function($sheet) use($weeks, $items) {
                    $col_array =[];
                    $col = 2;

                    $sheet->setCellValueByColumnAndRow(0,2, 'Customer Name');
                    $sheet->setCellValueByColumnAndRow(1,2, 'OSA Status');
                    foreach ($weeks as $week) {
                        $sheet->setCellValueByColumnAndRow($col,2, $week);
                        $col_array[$week] = $col;
                        $col++;
                    }

                    $sheet->setCellValueByColumnAndRow($col,2, 'Grand Total');

                    
                    $row = 3;
                    $last_col = count($col_array);
                    $x_total_col = $last_col + 2;
                    $last_row = (count($items)* 2) + 2;
                    foreach ($items as $key => $value) {
                        $next_row = $row + 1;
                        $sheet->setCellValueByColumnAndRow(0,$row, $key);
                        $sheet->mergeCells(\PHPExcel_Cell::stringFromColumnIndex(0).$row.":".\PHPExcel_Cell::stringFromColumnIndex(0).$next_row);
                        $sheet->setCellValueByColumnAndRow(1,$row, 0);
                        $sheet->setCellValueByColumnAndRow(1,$next_row, 1);

                        $failed_x_total = 0;
                        $passed_x_total = 0;
                        foreach ($value as $k => $rowValue) {
                            $wek_col = $col_array[$k];
                            $sheet->setCellValueByColumnAndRow($wek_col,$row, $rowValue['failed']);
                            $sheet->setCellValueByColumnAndRow($wek_col,$next_row, $rowValue['passed']);

                            $failed_x_total += $rowValue['failed'];
                            $passed_x_total += $rowValue['passed'];

                            $week_total = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($wek_col)."3:".\PHPExcel_Cell::stringFromColumnIndex($wek_col).$last_row.")";


                            $sheet->setCellValueByColumnAndRow($wek_col,$last_row+1, $week_total);

                        }
                        $sheet->setCellValueByColumnAndRow($x_total_col,$row, $failed_x_total);
                        $sheet->setCellValueByColumnAndRow($x_total_col,$next_row, $passed_x_total );
                        $row = $row + 2;
                    }
                    $gcol = $x_total_col;
                    $grand_total = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($gcol)."3:".\PHPExcel_Cell::stringFromColumnIndex($gcol).$last_row.")";
                    $sheet->setCellValueByColumnAndRow($gcol,$last_row+1, $grand_total);

                    $sheet->setCellValueByColumnAndRow(0,$row, 'Grand Total');
                    

                });
            })->download('xlsx');
        }
    }


    public function store()
    {
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

        $inventories = ItemInventories::getOsaPerStore($data);
        return view('osa.store', compact('inventories','frm', 'to', 'areas', 'sel_ar', 'sel_st'));
    }

    public function postStore(Request $request){
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
        $inventories = ItemInventories::getOsaPerStore($data);

        if ($request->has('submit')) {
            return view('osa.store', compact('inventories','frm', 'to', 'areas', 'sel_ar', 'sel_st'));
        }
        
        if ($request->has('download')) {
            \Excel::create('OSA Per Store', function($excel)  use ($inventories){
                $weeks = [];
                $items = [];
                foreach ($inventories as $value) {
                    $week_start = new \DateTime();
                    $week_start->setISODate($value->yr,$value->yr_week);
                    // $weeks[$week_start->getTimestamp()] = "Week ".$value->yr_week." of ".$value->yr;
                    $weeks[$week_start->format('Y-m-d')] = "Week ".$value->yr_week." of ".$value->yr;
                    $items[$value->area][$value->store_name]["Week ".$value->yr_week." of ".$value->yr] = ['passed' => $value->passed, 'failed' => $value->failed];


                }
                // dd($items);
                ksort($weeks);
                $excel->sheet('Sheet1', function($sheet) use ($items,$weeks) {
                    $col_array =[];
                    $col = 2;
                    foreach ($weeks as $week) {
                        $sheet->setCellValueByColumnAndRow($col,2, $week);
                        $sheet->setCellValueByColumnAndRow($col+2,2, $week." Total");
                        $n_col = $col+1;
                        $col_array[$week] = $col;
                        $sheet->mergeCells(\PHPExcel_Cell::stringFromColumnIndex($col)."2:".\PHPExcel_Cell::stringFromColumnIndex($n_col)."2");
                        
                        $col = $col +3;

                    }
                    $sheet->setCellValueByColumnAndRow($col,2, 'Grand Total');
                    
                    $area_col = 0;
                    $store_col = 1;
                    $sheet->setCellValueByColumnAndRow($area_col,3, 'AREA');
                    $sheet->setCellValueByColumnAndRow($store_col,3, 'STORE NAME');
                    foreach ($weeks as $week) {
                        $sheet->setCellValueByColumnAndRow($store_col+1,3, '0');
                        $sheet->setCellValueByColumnAndRow($store_col+2,3, '1');
                        $store_col = $store_col +3;
                    }

                    $row = 4;
                    $grand_total_col = (count($col_array) * 3)+2;

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
                            $grand_total = 0;
                            foreach ($record as $k => $rowValue) {
                                $fso_col = $col_array[$k];
                                $fso_val_col = $col_array[$k]+1;
                                $week_total = $rowValue['failed'] + $rowValue['passed'];
                                $week_failed_total = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($fso_col).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($fso_col).$last_row.")";
                                $week_passed_total = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($fso_col+1).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($fso_col+1).$last_row.")";                            
                                $week_store_item_total = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($fso_col+2).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($fso_col+2).$last_row.")";

                                $grand_total += $week_total;

                                $sheet->setCellValueByColumnAndRow($fso_col,$row, $rowValue['failed']);
                                $sheet->setCellValueByColumnAndRow($fso_col,$total_row, $week_failed_total);
                                $per_area_failed_total[$k][$key] = \PHPExcel_Cell::stringFromColumnIndex($fso_col).$total_row;

                                $sheet->setCellValueByColumnAndRow($fso_val_col,$row, $rowValue['passed']);
                                $sheet->setCellValueByColumnAndRow($fso_col+1,$total_row, $week_passed_total);
                                $per_area_passed_total[$k][$key] = \PHPExcel_Cell::stringFromColumnIndex($fso_col+1).$total_row;

                                $sheet->setCellValueByColumnAndRow($fso_val_col+1,$row, $week_total);
                                $sheet->setCellValueByColumnAndRow($fso_col+2,$total_row, $week_store_item_total);
                                $per_area_items_total[$k][$key] = \PHPExcel_Cell::stringFromColumnIndex($fso_col+2).$total_row;
                            }
                            $sheet->setCellValueByColumnAndRow($grand_total_col,$row, $grand_total);
                            $store_grand_total = "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($grand_total_col).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($grand_total_col).$last_row.")";
                            $sheet->setCellValueByColumnAndRow($grand_total_col,$total_row, $store_grand_total);

                            

                            $row++;
                        }
            
                        $sheet->setCellValueByColumnAndRow(0,$row, $key.' Total');
                        $g_total[] = \PHPExcel_Cell::stringFromColumnIndex($grand_total_col).$row;
                        $row++;
                    }
                    
                    $sheet->setCellValueByColumnAndRow(0,$row, 'Grand Total');

                    $col = 2;
                    foreach ($weeks as $week) {
                        $failed_total = [];
                        $passed_total = [];
                        $item_total = [];

                        $failed_cols = $per_area_failed_total[$week];
                        foreach ($failed_cols as $cell) {
                            $failed_total[] = $cell;
                        }

                        $passed_cols = $per_area_passed_total[$week];
                        foreach ($passed_cols as $cell) {
                            $passed_total[] = $cell;
                        }

                        $items_cols = $per_area_items_total[$week];
                        foreach ($items_cols as $cell) {
                            $item_total[] = $cell;
                        }


                        $sheet->setCellValueByColumnAndRow($col,$row, '=sum('.implode(",", $failed_total).')');
                        $sheet->setCellValueByColumnAndRow($col+1,$row, '=sum('.implode(",", $passed_total).')');
                        $sheet->setCellValueByColumnAndRow($col+2,$row, '=sum('.implode(",", $item_total).')');


                        $col = $col + 3;
                    }

                    $sheet->setCellValueByColumnAndRow($grand_total_col,$row, '=sum('.implode(",", $g_total).')');
                });
            })->download('xlsx');
        }
    }

   
}
