<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemInventories extends Model
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
			->join('store_inventories', 'store_inventories.id', '=', 'item_inventories.store_inventory_id')
			->get();

	}
}
