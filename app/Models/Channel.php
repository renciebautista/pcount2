<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
	protected $fillable = ['channel_code', 'channel_desc'];
    public $timestamps = false;
}
