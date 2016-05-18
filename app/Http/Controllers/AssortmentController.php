<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\AssortmentInventories;
use App\Models\AssortmentItemInventories;


class AssortmentController extends Controller
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

        $areas = AssortmentInventories::getAreaList();
        $sel_ar = [];
        $sel_st = [];
        // $sel_ar = AssortmentInventories::getStoreCodes('area');
        // $sel_st = AssortmentInventories::getStoreCodes('store_id');
        
        
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

        $inventories = AssortmentItemInventories::getAssortmentCompliance($data);
        
        return view('assortment.index', compact('inventories','frm', 'to', 'areas', 'sel_ar', 'sel_st'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sel_ar = $request->ar;
        $sel_st = $request->st;
        $frm = $request->fr;
        $to = $request->to;

        $areas = AssortmentInventories::getAreaList();
        // $sel_ar = AssortmentInventories::getStoreCodes('area');
        // $sel_st = AssortmentInventories::getStoreCodes('store_id');
        
        
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

        $inventories = AssortmentItemInventories::getAssortmentCompliance($data);

        if ($request->has('submit')) {
            return view('assortment.index', compact('inventories','frm', 'to', 'areas', 'sel_ar', 'sel_st'));
        }

        if ($request->has('download')) {
            \Excel::create('Assortment Compliance Report', function($excel)  use ($inventories){
                $weeks = [];
                $items = [];
                foreach ($inventories as $value) {
                    $week_start = new \DateTime();
                    $week_start->setISODate($value->yr,$value->yr_week);
                    $weeks[$week_start->format('Y-m-d')] = "Week ".$value->yr_week." of ".$value->yr;
                    $store_list[$value->area][$value->store_name] = $value;
                    $items[$value->area][$value->store_name]["Week ".$value->yr_week." of ".$value->yr] = ['passed' => $value->passed, 'failed' => $value->failed, 'client_name' => $value->client_name];
                }
                // dd($items);
                ksort($weeks);
                $excel->sheet('Sheet1', function($sheet) use ($items,$weeks,$store_list) {
                    $default_store_col = 7;
                    $col_array =[];
                    $col = 8;
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
                    $sheet->setCellValueByColumnAndRow(2,3, 'DISTRIBUTOR NAME');
                    $sheet->setCellValueByColumnAndRow(3,3, 'DISTRIBUTOR CODE');
                    $sheet->setCellValueByColumnAndRow(4,3, 'AGENCY');
                    $sheet->setCellValueByColumnAndRow(5,3, 'STORE CODE');
                    $sheet->setCellValueByColumnAndRow(6,3, 'STORE ID');
                    $sheet->setCellValueByColumnAndRow($store_col,3, 'STORE NAME');
                    foreach ($weeks as $week) {
                        $sheet->setCellValueByColumnAndRow($store_col+1,3, 'OOS');
                        $sheet->setCellValueByColumnAndRow($store_col+2,3, 'With Stocks');
                        $sheet->setCellValueByColumnAndRow($store_col+3,3, 'Total');
                        $sheet->setCellValueByColumnAndRow($store_col+4,3, 'Compliance Score');
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
                    $sheet->setCellValueByColumnAndRow($store_col+4,3, 'Total Compliance Score');
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
                        $client_name = '';
                        foreach ($value as $skey => $record) {
                            $oos_row_total = 0;
                            $withstock_row_total = 0;
                            $sheet->setCellValueByColumnAndRow(0,$row, $key );
                            $sheet->setCellValueByColumnAndRow(1,$row, $store_list[$key][$skey]->region_name);
                            $sheet->setCellValueByColumnAndRow(2,$row, $store_list[$key][$skey]->distributor);
                            $sheet->setCellValueByColumnAndRow(3,$row, $store_list[$key][$skey]->distributor_code);
                            $sheet->setCellValueByColumnAndRow(4,$row, $store_list[$key][$skey]->agency);
                            $sheet->setCellValueByColumnAndRow(5,$row, $store_list[$key][$skey]->store_code);
                            $sheet->setCellValueByColumnAndRow(6,$row, $store_list[$key][$skey]->store_id);
                            $sheet->setCellValueByColumnAndRow(7,$row, $skey);
                            $grand_total = 0;
                            foreach ($record as $k => $rowValue) {
                                $client_name = strtoupper($rowValue['client_name']);
                                $oos_col = $col_array[$k];
                                $with_stock_col = $col_array[$k]+1;
                                $store_total = $rowValue['failed'] + $rowValue['passed'];
                                if($client_name == 'MT MDC'){
                                    $osa_score = '=IFERROR('.\PHPExcel_Cell::stringFromColumnIndex($with_stock_col).$row.'/SUM('.\PHPExcel_Cell::stringFromColumnIndex($oos_col).$row.','.\PHPExcel_Cell::stringFromColumnIndex($with_stock_col).$row.'),"")';
                                }else{
                                    $osa_score = '=IFERROR('.\PHPExcel_Cell::stringFromColumnIndex($oos_col).$row.'/SUM('.\PHPExcel_Cell::stringFromColumnIndex($oos_col).$row.','.\PHPExcel_Cell::stringFromColumnIndex($with_stock_col).$row.'),"")';
                                }

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
                            if($client_name == 'MT MDC'){
                                $osa_score_total = '=IFERROR('.\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$row.'/SUM('.\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$row.','.\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$row.'),"")';
                            }else{
                                $osa_score_total = '=IFERROR('.\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$row.'/SUM('.\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$row.','.\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$row.'),"")';
                            }
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
                            if($client_name == 'MT MDC'){
                                $sheet->setCellValueByColumnAndRow($store_col+4,$row, '=IFERROR('.\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$row.'/SUM('.\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$row.','.\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$row.'),"")');
                            }else{
                                $sheet->setCellValueByColumnAndRow($store_col+4,$row, '=IFERROR('.\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$row.'/SUM('.\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$row.','.\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$row.'),"")');
                            }
                            $store_col = $store_col +4;
                        }

                        $sheet->setCellValueByColumnAndRow($store_col+1,$row, "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$last_row.")");
                        $sheet->setCellValueByColumnAndRow($store_col+2,$row, "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$last_row.")");
                        $sheet->setCellValueByColumnAndRow($store_col+3,$row, "=SUM(".\PHPExcel_Cell::stringFromColumnIndex($store_col+3).$start_row.":".\PHPExcel_Cell::stringFromColumnIndex($store_col+3).$last_row.")");
                        if($client_name == 'MT MDC'){
                            $sheet->setCellValueByColumnAndRow($store_col+4,$row, '=IFERROR('.\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$row.'/SUM('.\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$row.','.\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$row.'),"")');
                        }else{
                            $sheet->setCellValueByColumnAndRow($store_col+4,$row, '=IFERROR('.\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$row.'/SUM('.\PHPExcel_Cell::stringFromColumnIndex($store_col+2).$row.','.\PHPExcel_Cell::stringFromColumnIndex($store_col+1).$row.'),"")');
                        }
                        $row++;
                    }
                    // $sheet->setCellValueByColumnAndRow(0,$row, 'Grand Total');

                    // $store_col = 1;
                    // foreach ($weeks as $week) {
                    //     $oos_row_cells = [];
                    //     $withstock_row_cells = [];
                    //     $total_row_cells =[];
                    //     $oos_col = $col_array[$week];
                    //     foreach ($per_area_total_rows as $cell) {
                    //         $oos_row_cells[] = \PHPExcel_Cell::stringFromColumnIndex($oos_col).$cell;
                    //         $withstock_row_cells[] = \PHPExcel_Cell::stringFromColumnIndex($oos_col+1).$cell;
                    //         $total_row_cells[] = \PHPExcel_Cell::stringFromColumnIndex($oos_col+2).$cell;
                    //     }

                    //     $sheet->setCellValueByColumnAndRow($oos_col,$row, '=sum('.implode(",", $oos_row_cells).')');
                    //     $sheet->setCellValueByColumnAndRow($oos_col+1,$row, '=sum('.implode(",", $withstock_row_cells).')');
                    //     $sheet->setCellValueByColumnAndRow($oos_col+2,$row, '=sum('.implode(",", $total_row_cells).')');
                    //     $sheet->setCellValueByColumnAndRow($oos_col+3,$row, '=IFERROR('.\PHPExcel_Cell::stringFromColumnIndex($oos_col+1).$row.'/SUM('.\PHPExcel_Cell::stringFromColumnIndex($oos_col).$row.','.\PHPExcel_Cell::stringFromColumnIndex($oos_col+1).$row.'),"")');
                    // }

                    // $oos_row_cells = [];
                    // $withstock_row_cells = [];
                    // $total_row_cells =[];
                    // $oos_col = (count($col_array) * 4)+1;
                    // // dd($oos_col);
                    // foreach ($per_area_total_rows as $cell) {
                    //     $oos_row_cells[] = \PHPExcel_Cell::stringFromColumnIndex($oos_col+1).$cell;
                    //     $withstock_row_cells[] = \PHPExcel_Cell::stringFromColumnIndex($oos_col+2).$cell;
                    //     $total_row_cells[] = \PHPExcel_Cell::stringFromColumnIndex($oos_col+3).$cell;
                    // }

                    // $sheet->setCellValueByColumnAndRow($oos_col+1,$row, '=sum('.implode(",", $oos_row_cells).')');
                    // $sheet->setCellValueByColumnAndRow($oos_col+2,$row, '=sum('.implode(",", $withstock_row_cells).')');
                    // $sheet->setCellValueByColumnAndRow($oos_col+3,$row, '=sum('.implode(",", $total_row_cells).')');
                    // $sheet->setCellValueByColumnAndRow($oos_col+4,$row, '=IFERROR('.\PHPExcel_Cell::stringFromColumnIndex($oos_col+2).$row.'/SUM('.\PHPExcel_Cell::stringFromColumnIndex($oos_col+1).$row.','.\PHPExcel_Cell::stringFromColumnIndex($oos_col+2).$row.'),"")');

                    
                });
            })->download('xlsx');
        }

    }

    
}
