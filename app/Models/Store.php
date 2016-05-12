<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    public $timestamps = false;

    public function area()
    {
        return $this->belongsTo('App\Models\Area');
    }

    public function enrollment()
    {
        return $this->belongsTo('App\Models\Enrollment');
    }

    public function distributor()
    {
        return $this->belongsTo('App\Models\Distributor');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function channel()
    {
        return $this->belongsTo('App\Models\Channel');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region');
    }

    public function agency()
    {
        return $this->belongsTo('App\Models\Agency');
    }
   
    public static function search($request){
        // return self::with(['area' => function ($query) {
        //         $query->orderBy('area', 'desc');
        //     }])
            return self::where('store_name', 'LIKE', "%$request->search%")
            ->paginate(100)
            ->appends(['search' => $request->search]);
    }
}
