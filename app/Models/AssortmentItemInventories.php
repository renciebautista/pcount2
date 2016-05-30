<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssortmentItemInventories extends Model
{
    public $timestamps = false;

    public static function getDivisionList(){
        return self::select('division')
            ->groupBy('division')
            ->lists('division', 'division');
    }

    public static function getItemCodes($value){
    	$list = array();
    	$records = self::select($value)
    		->groupBy($value)
    		->get();
    	foreach ($records as $row) {
    		$list[] = (string)$row->$value;
    	}
    	return $list;
    }

    public static function filter($filters){

		return self::where(function($query) use ($filters){
			if(!empty($filters['from'])){
					$date = explode("-", $filters['from']);
					$query->where('transaction_date', '>=', $date[2].'-'.$date[0].'-'.$date[1]);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['to'])){
					$date = explode("-", $filters['to']);
					$query->where('transaction_date', '<=',  $date[2].'-'.$date[0].'-'.$date[1]);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['agencies'])){
					$query->whereIn('agency_code', $filters['agencies']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['clients'])){
					$query->whereIn('client_code', $filters['clients']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['channels'])){
					$query->whereIn('channel_code', $filters['channels']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['distributors'])){
					$query->whereIn('distributor_code', $filters['distributors']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['enrollments'])){
					$query->whereIn('enrollment_type', $filters['enrollments']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['regions'])){
					$query->whereIn('region_code', $filters['regions']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['stores'])){
					$query->whereIn('store_id', $filters['stores']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['divisions'])){
					$query->whereIn('division', $filters['divisions']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['categories'])){
					$query->whereIn('category', $filters['categories']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['subcategories'])){
					$query->whereIn('sub_category', $filters['subcategories']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['brands'])){
					$query->whereIn('brand', $filters['brands']);
				}
			})
			->join('assortment_inventories', 'assortment_inventories.id', '=', 'assortment_item_inventories.store_inventory_id')
			->orderBy('created_at', 'desc')
			->paginate(1000);
			// ->get();
	}

	
	public static function getPartial($filters,$take,$skip){

		return self::select(\DB::raw('area, region_name, distributor, distributor_code, store_id, store_code,store_name, other_barcode,sku_code,
			division, brand, category, sub_category,description,ig,fso_multiplier,sapc,whpc,whcs,
			so,fso,fso_val,osa,oos,transaction_date,created_at,signature'))
			->where(function($query) use ($filters){
			if(!empty($filters['from'])){
					$date = explode("-", $filters['from']);
					$query->where('transaction_date', '>=', $date[2].'-'.$date[0].'-'.$date[1]);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['to'])){
					$date = explode("-", $filters['to']);
					$query->where('transaction_date', '<=',  $date[2].'-'.$date[0].'-'.$date[1]);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['agencies'])){
					$query->whereIn('agency_code', $filters['agencies']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['clients'])){
					$query->whereIn('client_code', $filters['clients']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['channels'])){
					$query->whereIn('channel_code', $filters['channels']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['distributors'])){
					$query->whereIn('distributor_code', $filters['distributors']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['enrollments'])){
					$query->whereIn('enrollment_type', $filters['enrollments']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['regions'])){
					$query->whereIn('region_code', $filters['regions']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['stores'])){
					$query->whereIn('store_id', $filters['stores']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['divisions'])){
					$query->whereIn('division', $filters['divisions']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['categories'])){
					$query->whereIn('category', $filters['categories']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['subcategories'])){
					$query->whereIn('sub_category', $filters['subcategories']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['brands'])){
					$query->whereIn('brand', $filters['brands']);
				}
			})
			->join('assortment_inventories', 'assortment_inventories.id', '=', 'assortment_item_inventories.store_inventory_id')
			->skip($skip*$take)
			->take($take)
			->get();
	}


	public static function getSoPerArea($filters = null){
		return self::select('area',\DB::raw('SUBSTRING(yearweek(transaction_date,3),1,4) as yr,week(transaction_date,3) as yr_week, sum(fso) as fso_sum, sum(fso_val) as fso_val_sum'))
			->where(function($query) use ($filters){
			if(!empty($filters['areas'])){
					$query->whereIn('area', $filters['areas']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['from'])){
					$date = explode("-", $filters['from']);
					$query->where('transaction_date', '>=', $date[2].'-'.$date[0].'-'.$date[1]);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['to'])){
					$date = explode("-", $filters['to']);
					$query->where('transaction_date', '<=',  $date[2].'-'.$date[0].'-'.$date[1]);
				}
			})

			->join('assortment_inventories', 'assortment_inventories.id', '=', 'assortment_item_inventories.store_inventory_id')
			->groupBy(\DB::raw('yr, yr_week, area'))
			->orderBy(\DB::raw('yr, yr_week, area'))
			->get();

	}


	public static function getSoPerStores($filters = null){
		return self::select(\DB::raw('area, store_code, store_name,SUBSTRING(yearweek(transaction_date,3),1,4) as yr,week(transaction_date,3) as yr_week, sum(fso) as fso_sum, sum(fso_val) as fso_val_sum'))
			->where(function($query) use ($filters){
			if(!empty($filters['areas'])){
					$query->whereIn('area', $filters['areas']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['stores'])){
					$query->whereIn('store_id', $filters['stores']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['from'])){
					$date = explode("-", $filters['from']);
					$query->where('transaction_date', '>=', $date[2].'-'.$date[0].'-'.$date[1]);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['to'])){
					$date = explode("-", $filters['to']);
					$query->where('transaction_date', '<=',  $date[2].'-'.$date[0].'-'.$date[1]);
				}
			})

			->join('assortment_inventories', 'assortment_inventories.id', '=', 'assortment_item_inventories.store_inventory_id')
			->groupBy(\DB::raw('area, store_name, yr, yr_week'))
			->orderBy(\DB::raw('area, store_name, yr, yr_week'))
			->get();

	}

	public static function getOsaPerArea($filters = null){
		return self::select(\DB::raw('area,
			count(
				case
					when osa = 1
					then 1
					else null
				end
			) as passed,
			count(
				case
					when osa = 0
					then 0
					else null
				end
			) as failed,
			count(*) as total,
			SUBSTRING(yearweek(transaction_date,3),1,4) as yr,week(transaction_date,3) as yr_week'))
			->where(function($query) use ($filters){
			if(!empty($filters['areas'])){
					$query->whereIn('area', $filters['areas']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['from'])){
					$date = explode("-", $filters['from']);
					$query->where('transaction_date', '>=', $date[2].'-'.$date[0].'-'.$date[1]);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['to'])){
					$date = explode("-", $filters['to']);
					$query->where('transaction_date', '<=',  $date[2].'-'.$date[0].'-'.$date[1]);
				}
			})

			->join('assortment_inventories', 'assortment_inventories.id', '=', 'assortment_item_inventories.store_inventory_id')
			->groupBy(\DB::raw('yr, yr_week, area'))
			->orderBy(\DB::raw('yr, yr_week, area'))
			->get();

	}

	public static function getOsaPerStore($filters = null){
		return self::select(\DB::raw('area, region_name, distributor_code, distributor, agency, store_id, store_code, store_name, channel_code, channel_name,
			count(
				case
					when osa = 1
					then 1
					else null
				end
			) as passed,
			count(
				case
					when osa = 0
					then 0
					else null
				end
			) as failed,
			count(*) as total,
			SUBSTRING(yearweek(transaction_date,3),1,4) as yr,week(transaction_date,3) as yr_week'))
			->where(function($query) use ($filters){
			if(!empty($filters['areas'])){
					$query->whereIn('area', $filters['areas']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['stores'])){
					$query->whereIn('store_id', $filters['stores']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['from'])){
					$date = explode("-", $filters['from']);
					$query->where('transaction_date', '>=', $date[2].'-'.$date[0].'-'.$date[1]);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['to'])){
					$date = explode("-", $filters['to']);
					$query->where('transaction_date', '<=',  $date[2].'-'.$date[0].'-'.$date[1]);
				}
			})

			->join('assortment_inventories', 'assortment_inventories.id', '=', 'assortment_item_inventories.store_inventory_id')
			->groupBy(\DB::raw('area, store_name, yr, yr_week'))
			->orderBy(\DB::raw('area, store_name, yr, yr_week'))
			->get();

	}

	public static function getOosPerStore($filters = null){
		return self::select(\DB::raw('area, store_id, store_name, store_code,channel_name,other_barcode,
				sku_code, description, SUBSTRING(yearweek(transaction_date,3),1,4) as yr,week(transaction_date,3) as yr_week,transaction_date, sum(oos) as oos'))
			->where(function($query) use ($filters){
			if(!empty($filters['areas'])){
					$query->whereIn('area', $filters['areas']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['stores'])){
					$query->whereIn('store_id', $filters['stores']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['from'])){
					$date = explode("-", $filters['from']);
					$query->where('transaction_date', '>=', $date[2].'-'.$date[0].'-'.$date[1]);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['to'])){
					$date = explode("-", $filters['to']);
					$query->where('transaction_date', '<=',  $date[2].'-'.$date[0].'-'.$date[1]);
				}
			})

			->join('assortment_inventories', 'assortment_inventories.id', '=', 'assortment_item_inventories.store_inventory_id')
			->groupBy(\DB::raw('area, store_name, sku_code, transaction_date'))
			->orderBy(\DB::raw('area, area, store_name, description, transaction_date'))
			->get();

	}


	public static function getDays($from,$to){
		$fromdate = explode("-", $from);
		$todate = explode("-", $to);
		$query = sprintf("select * from 
                (select adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) date from
                 (select 0 t0 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
                 (select 0 t1 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
                 (select 0 t2 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
                 (select 0 t3 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
                 (select 0 t4 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) v
                where date between '%s' and '%s'",$fromdate[2].'-'.$fromdate[0].'-'.$fromdate[1], $todate[2].'-'.$todate[0].'-'.$todate[1]);
		return \DB::select(\DB::raw($query));
	}

	public static function getAssortmentCompliance($filters){
		return self::select(\DB::raw('area, region_name, distributor , distributor_code, agency, store_id, store_code, store_name, client_name,
			count(
				case
					when osa = 1
					then 1
					else null
				end
			) as passed,
			count(
				case
					when osa = 0
					then 0
					else null
				end
			) as failed,
			count(*) as total,
			SUBSTRING(yearweek(transaction_date,3),1,4) as yr,week(transaction_date,3) as yr_week'))
			->where(function($query) use ($filters){
			if(!empty($filters['areas'])){
					$query->whereIn('area', $filters['areas']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['stores'])){
					$query->whereIn('store_id', $filters['stores']);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['from'])){
					$date = explode("-", $filters['from']);
					$query->where('transaction_date', '>=', $date[2].'-'.$date[0].'-'.$date[1]);
				}
			})
			->where(function($query) use ($filters){
			if(!empty($filters['to'])){
					$date = explode("-", $filters['to']);
					$query->where('transaction_date', '<=',  $date[2].'-'.$date[0].'-'.$date[1]);
				}
			})

			->join('assortment_inventories', 'assortment_inventories.id', '=', 'assortment_item_inventories.store_inventory_id')
			->groupBy(\DB::raw('yr, store_name,yr_week, area'))
			->orderBy(\DB::raw('yr, store_name,yr_week, area'))
			->get();
	}
}
