<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\Dates;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

use App\Models\ItemInventories;


class ExportMkl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:mkl {start_date} {end_date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export MKL';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $startdate = $this->argument('start_date');
        $enddate = $this->argument('end_date');

        set_time_limit(0);
        ini_set('memory_limit', -1);

        $timeFirst  = strtotime(date('Y-m-d H:i:s'));

        $filePath = storage_path().'/report/postedmkl/Posted MKL '.$startdate.' - '. $enddate.'.csv';
        $dates = Dates::getDatesFromRange($startdate, $enddate);
        $writer = WriterFactory::create(Type::CSV); 
        // $writer->setShouldCreateNewSheetsAutomatically(true); // default value
        $writer->openToFile($filePath);
        $writer->addRow(array('AREA', 'REGION', 'DISTRIBUTOR', 'DISTRIBUTOR CODE', 'STORE ID', 
            'STORE CODE', 'STORE NAME', 'OTHER CODE', 'SKU CODE', 'DIVISION', 'BRAND', 'CATEGORY', 'SUB CATEGORY',
            'ITEM DESCRIPTION', 'IG', 'FSO MULTIPLIER', 'SAPC',
            'WHPC', 'WHCS', 'SO', 'FSO', 'FSO VAL', 'OSA', 'OSS', 'TRANSACTION DATE', 'POSTING DATE AND TIME', 'SIGNATURE LINK'));

        foreach ($dates as $date) {
            $rows = NULL;
            $rows = ItemInventories::getByDate($date);
            $plunck_data = [];
            foreach($rows as $row)
            {
                if(!is_null($row->signature)){
                    $link = url('api/pcountimage', [$row->signature]);
                    
                }else{
                    $link = '';
                }
                $row_data[0] = $row->area;
                $row_data[1] = $row->region_name;
                $row_data[2] = $row->distributor;
                $row_data[3] = $row->distributor_code;
                $row_data[4] = $row->store_id;
                $row_data[5] = $row->store_code;
                $row_data[6] = $row->store_name;
                $row_data[7] = $row->other_barcode;
                $row_data[8] = $row->sku_code;
                $row_data[9] = $row->division;
                $row_data[10] = $row->brand;
                $row_data[11] = $row->category;
                $row_data[12] = $row->sub_category;
                $row_data[13] = $row->description;
                $row_data[14] = $row->ig;
                $row_data[15] = $row->fso_multiplier;
                $row_data[16] = $row->sapc;
                $row_data[17] = $row->whpc;
                $row_data[18] = $row->whcs;
                $row_data[19] = $row->so;
                $row_data[20] = $row->fso;
                $row_data[21] = (double)$row->fso_val;
                $row_data[22] = $row->osa;
                $row_data[23] = $row->oos;
                $row_data[24] = $row->transaction_date;
                $row_data[25] = $row->created_at;
                $row_data[26] = $link;
                $plunck_data[] = $row_data;
            }

            $writer->addRows($plunck_data); // add multiple rows at a time
        }       

        $writer->close(); 

        $timeSecond = strtotime(date('Y-m-d H:i:s'));
        $differenceInSeconds = $timeSecond - $timeFirst;
        echo  'Time used ' . $differenceInSeconds . " sec";

    }
}
