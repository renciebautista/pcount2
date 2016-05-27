<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryRepositiry extends Model
{
    public static function getHistory($filters){
    	$from = '';
        if(!empty($filters['from'])){
            $date = explode("-", $filters['from']);
            $from = "where transaction_date >= '" . $date[2].'-'.$date[0].'-'.$date[1]."'";
        }
        $to = '';
        if(!empty($filters['to'])){
            $date = explode("-", $filters['to']);
            $to = " and transaction_date <= '" . $date[2].'-'.$date[0].'-'.$date[1]."'";
        }
        $agencies = '';
        if(!empty($filters['agencies'])){
            $agencies = " and agency_code in ('" . implode(",", $filters['agencies']). "')";
        }

        $channels = '';
        if(!empty($filters['channels'])){
            $channels = " and channel_code in ('" . implode(",", $filters['channels']). "')";
        }

        $regions = '';
        if(!empty($filters['regions'])){
            $agencies = " and region_code in ('" . implode(",", $filters['regions']). "')";
        }
        $stores = '';
        if(!empty($filters['stores'])){
            $stores = " and store_code in ('" . implode(",", $filters['stores']). "')";
        }

        $users = '';
        if(!empty($filters['users'])){
            $users = " and username in ('" . implode(",", $filters['users']). "')";
        }

        $types = '';
        if(!empty($filters['types'])){
            $types = " and post_type in ('" . implode(",", $filters['types']). "')";
        }

    	$query = sprintf("select * from (
			select agency_code, agency, region_code, region_name, channel_code, channel_name, distributor, store_name, store_code, username, transaction_date, updated_at, 'OSA' as type, '1' as post_type
			from store_inventories
			union all
			select agency_code, agency, region_code, region_name, channel_code, channel_name, distributor, store_name, store_code, username, transaction_date, updated_at, 'Assortment' as type, '2' as post_type
			from assortment_inventories
			) as tbl
    		%s
    		%s
    		%s
    		%s
    		%s
    		%s
    		%s
    		%s
			order by updated_at desc",
			$from, $to, $agencies, $regions, $channels,$stores, $users, $types);
    	return \DB::select(\DB::raw($query));
    }
}
