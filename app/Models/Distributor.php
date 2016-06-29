<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
	protected $fillable = ['distributor_code', 'distributor'];
    public $timestamps = false;
}
