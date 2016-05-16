<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword,EntrustUserTrait{
        EntrustUserTrait::can as may;
        Authorizable::can insteadof EntrustUserTrait;
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function roles()
    {
        return $this->belongsToMany('App\Role','role_user');
    }

    public function isActive(){
        return $this->attributes['active'];
    }

    public static function search($request){
        return self::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->where(function($query) use ($request){
            if ($request->has('role')) {
                    $query->where('role_id', $request->role);
                }
            })
            ->where(function($query) use ($request){
                $query->where('name', 'LIKE', "%$request->search%");
                $query->orWhere('username', 'LIKE', "%$request->search%");
                $query->orWhere('email', 'LIKE', "%$request->search%");
            })
            ->get();

    }

}
