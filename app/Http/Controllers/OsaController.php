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

   
}
