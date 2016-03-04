<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    public $timestamps = false;
    public $table = 'role_user';
    public function user()
    {
        return $this->belongsTo('App\User','user_id', 'id');        
    }   
    public function role()
    {
        return $this->belongsTo('App\Role','role_id', 'id');        
    }   
}
	