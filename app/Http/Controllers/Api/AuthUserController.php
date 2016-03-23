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

            if(($user->log_status == 0) || ($user->device_id == $device_id)){
                $user->log_status = 1;
                $user->device_id = $device_id;
                $user->last_login = date('Y-m-d H:i:s');
                $user->update();

                $hash = UpdateHash::find(1);
            
                $user->hash =  $hash->hash;
                return response()->json($user);
            }else{
                $t1 = StrToTime(date('Y-m-d H:i:s'));
                $t2 = StrToTime($user->last_login);
                $diff = ($t1 - $t2)/ ( 60 * 60 );
                if($diff >= 24){
                    $user->log_status = 1;
                    $user->device_id = $device_id;
                    $user->last_login = date('Y-m-d H:i:s');
                    $user->update();

                    $hash = UpdateHash::find(1);
                
                    $user->hash =  $hash->hash;
                    return response()->json($user);
                }else{
                    return response()->json(array('msg' => 'User already logged on another device.', 'status' => 0, 'hr' => $diff));
                }
            }
            
        }else{
            return response()->json(array('msg' => 'user not found', 'status' => 0));
        }
        
    }

    public function logout(Request $request){
        $device_id = $request->device_id;
        $usernameinput =  $request->email;
        $password = $request->pwd;
        $field = $usernameinput ? 'email' : 'username';
         if(\Auth::attempt(array('username' => $usernameinput, 'password' => $password), false)){
            $user = \Auth::user();
            $user->log_status = 0;
            $user->update();

            $hash = UpdateHash::find(1);
        
            $user->hash =  $hash->hash;
            return response()->json($user);
            
        }else{
            return response()->json(array('msg' => 'user not found', 'status' => 0));
        }
    }
}

