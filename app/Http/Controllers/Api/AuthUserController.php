<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Models\UpdateHash;

class AuthUserController extends Controller
{
    public function auth(Request $request){
        $device_id = $request->device_id;
        $usernameinput =  $request->email;
        $password = $request->pwd;
        $field = $usernameinput ? 'email' : 'username';
         if(\Auth::attempt(array('username' => $usernameinput, 'password' => $password), false)){
            $user = \Auth::user();
            $hash = UpdateHash::find(1);
            
            $user->hash =  $hash->hash;
            return response()->json($user);
        }else{
            return response()->json(array('msg' => 'user not found', 'status' => 0));
        }
        
    }
}



       // if(\Auth::attempt(array('username' => $usernameinput, 'password' => $password), false))
        // {
        //     $user = \Auth::user();
        //     if($user->log_status == 0)
        //     {
        //         $user->log_status = 1;
        //         $user->device_id = $device_id;
        //         $hash = UpdateHash::find(1);            
        //         $user->hash =  $hash->hash;
        //         return response()->json($user);
        //     }
        //     if($user->log_status==1)
        //     {
        //        $updated_at = Carbon::parse(date_format(date_create($user->updated_at),'Y-m-d H:i:s'));
        //        $date_now = Carbon::now();
        //        $duration = $date_now->diffInMinutes($updated_at); 
        //        $day = floor ($duration / 1440);    
        //        if($day >=1)
        //        {                
        //             $user->log_status = 1;
        //             $user->device_id = $device_id;
        //             $hash = UpdateHash::find(1);            
        //             $user->hash =  $hash->hash;
        //             return response()->json($user);            
        //        }
        //        if($day < 1)
        //        {
        //             if($user->device_id == $request->device_id)
        //             {
        //                 return response()->json(array('msg' => 'user not found', 'status' => 0));
        //             }
        //        }
            
        //     }else{
        //     return response()->json(array('msg' => 'user not found', 'status' => 0));
        //     }

        // }
        // print_r($request->all());
