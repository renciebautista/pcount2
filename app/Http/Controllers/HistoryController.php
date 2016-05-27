<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\HistoryRepositiry;
use App\Models\ComplianceRepository;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class HistoryController extends Controller
{
    public function posting(){

        $frm = date("m-d-Y");
        $to = date("m-d-Y");

        $agencies = ComplianceRepository::getAllAgency();
        $sel_ag = [];

        $regions = ComplianceRepository::getAllRegion();
        $sel_rg = [];

        $channels = ComplianceRepository::getAllChannel();
        $sel_ch = [];

        $stores = ComplianceRepository::getAllStore();
        $sel_st = [];

        $users = ComplianceRepository::getAllUser();
        $sel_us = [];
        
        $types = ['1' => 'OSA', '2' => 'Assoertment'];
        $sel_ty = [];
        
        
        
        if(!empty($frm)){
            $data['from'] = $frm;
        }
        if(!empty($to)){
            $data['to'] = $to;
        }
        if(!empty($sel_ag)){
            $data['agencies'] = $sel_ag;
        }
        if(!empty($sel_rg)){
            $data['regions'] = $sel_rg;
        }
        if(!empty($sel_ch)){
            $data['channels'] = $sel_ch;
        }
        if(!empty($sel_st)){
            $data['stores'] = $sel_st;
        }
        if(!empty($sel_us)){
            $data['users'] = $sel_us;
        }
        if(!empty($sel_ty)){
            $data['types'] = $sel_ty;
        }

        $postings = HistoryRepositiry::getHistory($data);
        return view('history.posting',compact('postings', 'frm', 'to', 'agencies', 'sel_ag',    
            'regions', 'sel_rg', 
            'channels', 'sel_ch',
            'stores', 'sel_st',
            'users', 'sel_us',
            'types', 'sel_ty'));
    }

    public function postposting(Request $request){

        $sel_ag = $request->ag;
        $sel_ch = $request->ch;
        $sel_rg = $request->rg;
        $sel_st = $request->st;
        $sel_us = $request->us;
        $sel_ty = $request->ty;

        $frm = $request->fr;
        $to = $request->to;

        $agencies = ComplianceRepository::getAllAgency();
        $regions = ComplianceRepository::getAllRegion();
        $channels = ComplianceRepository::getAllChannel();
        $stores = ComplianceRepository::getAllStore();
        $users = ComplianceRepository::getAllUser();
        $types = ['1' => 'OSA', '2' => 'Assoertment'];

        if(!empty($frm)){
            $data['from'] = $frm;
        }
        if(!empty($to)){
            $data['to'] = $to;
        }
        if(!empty($sel_ag)){
            $data['agencies'] = $sel_ag;
        }
        if(!empty($sel_rg)){
            $data['regions'] = $sel_rg;
        }
        if(!empty($sel_ch)){
            $data['channels'] = $sel_ch;
        }
        if(!empty($sel_st)){
            $data['stores'] = $sel_st;
        }
        if(!empty($sel_us)){
            $data['users'] = $sel_us;
        }
        if(!empty($sel_ty)){
            $data['types'] = $sel_ty;
        }


        $postings = HistoryRepositiry::getHistory($data);
        if ($request->has('submit')) {
            return view('history.posting',compact('postings', 'frm', 'to', 'agencies', 'sel_ag',    
                'regions', 'sel_rg', 
                'channels', 'sel_ch',
                'stores', 'sel_st',
                'users', 'sel_us',
                'types', 'sel_ty'));
        }

        if ($request->has('download')) {
            $fileName = "Posting History Report.csv";
            $writer = WriterFactory::create(Type::CSV); // for CSV files
            $writer->openToBrowser($fileName); // stream data directly to the browser

            $writer->addRow(array('Agency Code', 'Agency Name', 'Region Code', 'Region Name', 'Channel Code', 'Channel Name',
                'Distributor', 'Store Name', 'Store Code', 'Username', 'Transaction Date', 'Posting Date', 'Posting Type'));
            foreach ($postings as $row) {
                $_data[0] = $row->agency_code;
                $_data[1] = $row->agency;
                $_data[2] = $row->region_code;
                $_data[3] = $row->region_name;
                $_data[4] = $row->channel_code;
                $_data[5] = $row->channel_name;
                $_data[6] = $row->distributor;
                $_data[7] = $row->store_name;
                $_data[8] = $row->store_code;
                $_data[9] = $row->username;
                $_data[10] = $row->transaction_date;
                $_data[11] = $row->updated_at;
                $_data[12] = $row->type;

                $writer->addRow($_data); // add multiple rows at a time
            }
            
            $writer->close();
        }

    }
}
