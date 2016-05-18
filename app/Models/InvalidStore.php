<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvalidStore extends Model
{
    public static function invalid($row,$remarks){
    	self::create([
			'area' => $row[0],
			'enrollment_type' => $row[1],
			'distributor_code' => $row[2],
			'distributor' => $row[3],
			'storeid' => $row[4],
			'store_code' => $row[5],
			'store_code_psup' => $row[6],
			'store_name' => $row[7],
			'client_code' => $row[8],
			'client_name' => $row[9],
			'channel_code' => $row[10],
			'channel_name' => $row[11],
			'customer_code' => $row[12],
			'customer' => $row[13],
			'region_short' => $row[14],
			'region_name' => $row[15],
			'region_code' => $row[16],
			'fms' => $row[17],
			'fms_username' => $row[18],
			'agency_code' => $row[19],
			'agency_name' => $row[20],
			'lead_refillers' => $row[21],
			'username' => $row[22],
			'status' => $row[23],
			'remarks' => $remarks,
			]);
    }

    public static function search($request){
            return self::where('store_name', 'LIKE', "%$request->search%")
            ->paginate(100)
            ->appends(['search' => $request->search]);
    }
}
