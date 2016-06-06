<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Models\UpdateHash;
use App\Device;

class AuthUserController extends Controller
{
    public function getLastLogin(Request $request){
        $device_id = $request->device_id;
        $usernameinput =  $request->email;

        $user = User::where('username',$usernameinput)  
            ->where('device_id',$device_id)
            ->where('log_status', 1)
            ->first();

        if(empty($user)){
            return response()->json(array('msg' => 'Device database currently not sync', 'status' => 1));
        }else{
            $t1 = StrToTime(date('Y-m-d H:i:s'));
            $t2 = StrToTime($user->last_login);
            $diff = ($t1 - $t2)/ ( 60 * 60 );
            if($diff >= 24){
                return response()->json(array('msg' => 'Device database currently not sync', 'status' => 1));
            }else{
                return response()->json(array('msg' => 'Device database currently sync', 'status' => 0));
            }
        }
        
    }
    public function auth(Request $request){
        $device_id = $request->device_id;
        $usernameinput =  $request->email;
        $password = $request->pwd;
        $version = $request->version;

        // dd($request);

        $field = $usernameinput ? 'email' : 'username';
         if(\Auth::attempt(array('username' => $usernameinput, 'password' => $password), false)){
            $user = \Auth::user();

            $device = Device::where('device_id',$device_id)->first();

            if ($request->has('version')) {
                if(!empty($device)){
                    $device->version = $version;
                    $device->username = $usernameinput;
                    $device->update();
                }else{
                    Device::create(['device_id' => $device_id,
                     'version' => $version,
                     'username' => $usernameinput]);
                }
            }

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

        User::where('username',$usernameinput)  
            ->where('device_id',$device_id)
            ->update(['log_status' => 0]);

        return response()->json(array('msg' => 'Device successfuly logout', 'status' => 0));
    }

    public function checkhash(){
        $hash = UpdateHash::find(1);
        return response()->json($hash->hash);
    }
}

