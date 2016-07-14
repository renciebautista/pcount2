<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\StoreInventories;
use App\Models\ItemInventories;
use App\Models\AssortmentInventories;
use App\Models\AssortmentItemInventories;

class OsaController extends Controller
{

    public function area($type = null)
    {
        $frm = date("m-d-Y");
        $to = date("m-d-Y");
        $report_type = 1;

          $sel_av = [];
$availability =['1'=>'oos','2'=>'osa'];
        if((is_null($type)) || ($type != 'assortment')){
            $report_type = 2;
        }
        if($report_type == 2){
            $areas = StoreInventories::getAreaList();
            $sel_ar = StoreInventories::getStoreCodes('area');
        }else{
            $areas = AssortmentInventories::getAreaList();
            $sel_ar = AssortmentInventories::getStoreCodes('area');
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
        if($report_type == 2){
            $header = 'MKL OSA Per Area Report';
            $inventories = ItemInventories::getOsaPerArea($data);
        }else{
            $header = 'Assortment OSA Per Area Report';
            $inventories = AssortmentItemInventories::getOsaPerArea($data);
        }

        
        return view('osa.area', compact('inventories','frm', 'to', 'areas', 'sel_ar', 'header' , 'type','availability','sel_av'));
    }

    public function postArea(Request $request, $type = null){
        $sel_ar = $request->ar;

        $frm = $request->fr;
        $to = $request->to;
$sel_av = $request->availability;
  $availability =['1'=>'oos','2'=>'osa'];
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
        if(!empty($frm)){
            $data['from'] = $frm;
        }
        if(!empty($to)){
            $data['to'] = $to;
        }
        if(!empty($sel_av)){
            $data['availability'] = $sel_av;
        }
        if($report_type == 2){
            $header = 'MKL OSA Per Area Report';
            $inventories = ItemInventories::getOsaPerArea($data);
        }else{
            $header = 'Assortment OSA Per Area Report';
            $inventories = AssortmentItemInventories::getOsaPerArea($data);
        }

        if ($request->has('submit')) {
            return view('osa.area', compact('inventories','frm', 'to', 'areas', 'sel_ar', 'header' , 'type','sel_av','availability'));
        }
        
        if ($request->has('download')) {
            \Excel::create($header, function($excel)  use ($inventories){
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


    public function store($type = null)
    {
        $frm = date("m-d-Y");
        $to = date("m-d-Y");
        $report_type = 1;
          $sel_av = [];
$availability =['1'=>'oos','2'=>'osa'];
        if((is_null($type)) || ($type != 'assortment')){
            $report_type = 2;
        }
        $sel_ar = [];
        $sel_st = [];
        if($report_type == 2){
            $areas = StoreInventories::getAreaList();
            // $sel_ar = StoreInventories::getStoreCodes('area');
            // $sel_st = StoreInventories::getStoreCodes('store_id');
        }else{
            $areas = AssortmentInventories::getAreaList();
            // $sel_ar = AssortmentInventories::getStoreCodes('area');
            // $sel_st = AssortmentInventories::getStoreCodes('store_id');
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
        if($report_type == 2){
            $header = 'MKL OSA Per Store Report';
            $inventories = ItemInventories::getOsaPerStore($data);
        }else{
            $header = 'Assortment OSA Per Store Report';
            $inventories = AssortmentItemInventories::getOsaPerStore($data);
        }

        return view('osa.store', compact('inventories','frm', 'to', 'areas', 'sel_ar', 'sel_st','header' , 'type','availability','sel_av'));
    }

    public function postStore(Request $request,$type = null){
        $sel_ar = $request->ar;
        $sel_st = $request->st;
        $frm = $request->fr;
        $to = $request->to;
$sel_av = $request->availability;
  $availability =['1'=>'oos','2'=>'osa'];
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
        if($report_type == 2){
            $header = 'MKL OSA Per Store Report';
            $inventories = ItemInventories::getOsaPerStore($data);
        }else{
            $header = 'Assortment OSA Per Store Report';
            $inventories = AssortmentItemInventories::getOsaPerStore($data);
        }

        if ($request->has('submit')) {
            return view('osa.store', compact('inventories','frm', 'to', 'areas', 'sel_ar', 'sel_st','header' , 'type','availability','sel_av'));
        }

        // dd($inventories);

        if ($request->has('download')) {
            \Excel::create($header, function($excel)  use ($inventories){
                $weeks = [];
                $items = [];
                foreach ($inventories as $value) {
                    $week_start = new \DateTime();
                    $week_start->setISODate($value->yr,$value->yr_week);
                    $store_list[$value->area][$value->store_name] = $value;
                    $weeks[$week_start->format('Y-m-d')] = "Week ".$value->yr_week." of ".$value->yr;
                    $items[$value->area][$value->store_name]["Week ".$value->yr_week." of ".$value->yr] = ['passed' => $value->passed, 'failed' => $value->failed];


                }
                // dd($store_list);
                ksort($weeks);
                $excel->sheet('Sheet1', function($sheet) use ($items,$weeks,$store_list) {
                    $default_store_col = 9;
                    $col_array =[];
                    $col = 10;
                    foreach ($weeks as $week) {
                        $sheet->setCellValueByColumnAndRow($col,2, $week);
                        $n_col = $col+3;
                        $col_array[$week] = $col;
                        $sheet->mergeCells(\PHPExcel_Cell::stringFromColumnIndex($col)."2:".\PHPExcel_Cell::stringFromColumnIndex($n_col)."2");
                        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($col).'2')->getAlignment()->applyFromArray(
                            array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                        );
                        $col = $col +4;

                    }
                    $sheet->setCellValueByColumnAndRow($col,2, 'Grand Total');
                    $sheet->mergeCells(\PHPExcel_Cell::stringFromColumnIndex($col)."2:".\PHPExcel_Cell::stringFromColumnIndex($col+3)."2");
                    $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($col).'2')->getAlignment()->applyFromArray(
                        array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                    );
                    
                    $area_col = 0;
                    $store_col = $default_store_col;
                    $sheet->setCellValueByColumnAndRow($area_col,3, 'AREA');
                    $sheet->setCellValueByColumnAndRow(1,3, 'REGION NAME');
                    $sheet->setCellValueByColumnAndRow(2,3, 'DISTRIBUTOR CODE');
                    $sheet->setCellValueByColumnAndRow(3,3, 'DISTRIBUTOR NAME');
                    $sheet->setCellValueByColumnAndRow(4,3, 'AGENCY');
                    $sheet->setCellValueByColumnAndRow(5,3, 'STORE CODE');
                    $sheet->setCellValueByColumnAndRow(6,3, 'STORE ID');
                    $sheet->setCellValueByColumnAndRow(7,3, 'CHANNEL CODE');
                    $sheet->setCellValueByColumnAndRow(8,3, 'CHANNEL NAME');
                    $sheet->setCellValueByColumnAndRow($store_col,3, 'STORE NAME');
                    foreach ($weeks as $week) {
                        $sheet->setCellValueByColumnAndRow($store_col+1,3, 'OOS');
                        $sheet->setCellValueByColumnAndRow($store_col+2,3, 'With Stocks');
                        $sheet->setCellValueByColumnAndRow($store_col+3,3, 'Total');
                        $sheet->setCellValueByColumnAndRow($store_col+4,3, 'OSA Score');
                        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($store_col+4))
                            ->getNumberFormat()->applyFromArray(
                                array( 
                                'code' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00
                            )
                        );
                        $store_col = $store_col +4;
                    }

                    $sheet->setCellValueByColumnAndRow($store_col+1,3, 'Total OOS');
                    $sheet->setCellValueByColumnAndRow($store_col+2,3, 'Total With Stocks');
                    $sheet->setCellValueByColumnAndRow($store_col+3,3, 'Grand Total');
                    $sheet->setCellValueByColumnAndRow($store_col+4,3, 'Total OSA Score');
                    $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($store_col+4))
                        ->getNumberFormat()->applyFromArray(
                            array( 
                            'code' => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00
                        )
                    );

                    $row = 4;
                    // dd($items);
                    $per_area_total_rows = [];
                    foreach ($items as $key => $value) {
                        $first = true;
                        $total_row = count($value)+$row;
                        $last_row = $total_row - 1;
                        $start_row = $row;
                        foreach ($value as $skey => $record) {
                            $oos_row_total = 0;
                            $withstock_row_total = 0;
                            $sheet->setCellValueByColumnAndRow(0,$row, $key );
                            $sheet->setCellValueByColumnAndRow(1,$row, $store_list[$key][$skey]->region_name);
                            $sheet->setCellValueByColumnAndRow(2,$row, $store_list[$key][$skey]->distributor_code);
                            $sheet->setCellValueByColumnAndRow(3,$row, $store_list[$key][$skey]->distributor);
                            $sheet->setCellValueByColumnAndRow(4,$row, $store_list[$key][$skey]->agency);
                            $sheet->setCellValueByColumnAndRow(5,$row, $store_list[$key][$skey]->store_code);
                            $sheet->setCellValueByColumnAndRow(6,$row, $store_list[$key][$skey]->store_id);
                            $sheet->setCellValueByColumnAndRow(7,$row, $store_list[$key][$skey]->channel_code);
                            $sheet->setCellValueByColumnAndRow(8,$row, $store_list[$key][$skey]->channel_name);
                            $sheet->setCellValueByColumnAndRow(9,$row, $skey);
                            $grand_total = 0;
                            foreach ($record as $k => $rowValue) {
                                $oos_col = $col_array[$k];
                                $with_stock_col = $col_array[$k]+1;
                                $store_total = $rowValue['failed'] + $rowValue['passed'];
                                $osa_score = '=IFERROR('.\PHPExcel_Cell::stringFromColumnIndex($with_stock_col).$row.'/SUM('.\PHPExcel_Cell::stringFromColumnIndex($oos_col).$row.','.\PHPExcel_Cell::stringFromColumnIndex($with_stock_col).$row.'),"")';

                                //oos
                                $sheet->setCellValueByColumnAndRow($oos_col,$row, $rowValue['failed']);
                                $oos_row_total += $rowValue['failed'];
                                // //with stocks
                                $sheet->setCellValueByColumnAndRow($with_stock_col,$row, $rowValue['passed']);
                                $withstock_row_total += $rowValue['passed'];
                                // //total
                                $sheet->setCellValueByColumnAndRow($oos_col+2,$row, $store_total);
                                $sheet->setCellValueByColumnAndRow($oos_col+3,$row, $osa_score);

                            }

                            $sheet->setCellValueByColumnAndRow($store_col+1,$row, $oos_row_total);
                            $sheet->setCellValueByColumnAndRow($store_col+2,$row, $withstock_row_total);
                            $sheet->setCellValueByColumnAndRow($store_col+3,$row, $oos_row_total+$withstock_row_total);
                            $osa_score_total = '=IFERROR('.\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$row.'/SUM('.\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$row.','.\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$row.'),"")';
                            $sheet->setCellValueByColumnAndRow($store_col+4,$row, $osa_score_total);

                            

                            $row++;
                        }
                        $per_area_total_rows[] = $row;
                        $sheet->setCellValueByColumnAndRow(0,$row, $key.' Total');
                        $store_col = $default_store_col;
                        foreach ($weeks as $week) {
                            $sheet->setCellValueByColumnAndRow($store_col+1,$row, "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$last_row.")");
                            $sheet->setCellValueByColumnAndRow($store_col+2,$row, "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$last_row.")");
                            $sheet->setCellValueByColumnAndRow($store_col+3,$row, "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($store_col+3).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($store_col+3).$last_row.")");
                            $sheet->setCellValueByColumnAndRow($store_col+4,$row, '=IFERROR('.\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$row.'/SUM('.\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$row.','.\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$row.'),"")');
                            $store_col = $store_col +4;
                        }

                        $sheet->setCellValueByColumnAndRow($store_col+1,$row, "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$last_row.")");
                        $sheet->setCellValueByColumnAndRow($store_col+2,$row, "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$last_row.")");
                        $sheet->setCellValueByColumnAndRow($store_col+3,$row, "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($store_col+3).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($store_col+3).$last_row.")");
                        $sheet->setCellValueByColumnAndRow($store_col+4,$row, '=IFERROR('.\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$row.'/SUM('.\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$row.','.\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$row.'),"")');
                        $row++;
                    }
                    $sheet->setCellValueByColumnAndRow(0,$row, 'Grand Total');

                    $store_col = $default_store_col;
                    foreach ($weeks as $week) {
                        $oos_row_cells = [];
                        $withstock_row_cells = [];
                        $total_row_cells =[];
                        $oos_col = $col_array[$week];
                        foreach ($per_area_total_rows as $cell) {
                            $oos_row_cells[] = \PHPExcel_Cell::stringFromColumnIndex($oos_col).$cell;
                            $withstock_row_cells[] = \PHPExcel_Cell::stringFromColumnIndex($oos_col+1).$cell;
                            $total_row_cells[] = \PHPExcel_Cell::stringFromColumnIndex($oos_col+2).$cell;
                        }

                        $sheet->setCellValueByColumnAndRow($oos_col,$row, '=sum('.implode(",", $oos_row_cells).')');
                        $sheet->setCellValueByColumnAndRow($oos_col+1,$row, '=sum('.implode(",", $withstock_row_cells).')');
                        $sheet->setCellValueByColumnAndRow($oos_col+2,$row, '=sum('.implode(",", $total_row_cells).')');
                        $sheet->setCellValueByColumnAndRow($oos_col+3,$row, '=IFERROR('.\PHPExcel_Cell::stringFromColumnIndex($oos_col+1).$row.'/SUM('.\PHPExcel_Cell::stringFromColumnIndex($oos_col).$row.','.\PHPExcel_Cell::stringFromColumnIndex($oos_col+1).$row.'),"")');
                    }

                    $oos_row_cells = [];
                    $withstock_row_cells = [];
                    $total_row_cells =[];
                    $oos_col = (count($col_array) * 4)+6;
                    // dd($oos_col);
                    foreach ($per_area_total_rows as $cell) {
                        $oos_row_cells[] = \PHPExcel_Cell::stringFromColumnIndex($oos_col+1).$cell;
                        $withstock_row_cells[] = \PHPExcel_Cell::stringFromColumnIndex($oos_col+2).$cell;
                        $total_row_cells[] = \PHPExcel_Cell::stringFromColumnIndex($oos_col+3).$cell;
                    }

                    $sheet->setCellValueByColumnAndRow($oos_col+1,$row, '=sum('.implode(",", $oos_row_cells).')');
                    $sheet->setCellValueByColumnAndRow($oos_col+2,$row, '=sum('.implode(",", $withstock_row_cells).')');
                    $sheet->setCellValueByColumnAndRow($oos_col+3,$row, '=sum('.implode(",", $total_row_cells).')');
                    $sheet->setCellValueByColumnAndRow($oos_col+4,$row, '=IFERROR('.\PHPExcel_Cell::stringFromColumnIndex($oos_col+2).$row.'/SUM('.\PHPExcel_Cell::stringFromColumnIndex($oos_col+1).$row.','.\PHPExcel_Cell::stringFromColumnIndex($oos_col+2).$row.'),"")');

                    
                });
            })->download('xlsx');
        }
    }

   
}
