<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Models\User;

use App\Helpers\JwtAuth;
class UserController extends Controller
{
    //
    public function register(Request $request){
        $json = $request->input('json',null);
        
        $params = json_decode($json);
        $email = (!is_null($json) && isset($params->email))?$params->email:null;
        $name = (!is_null($json) && isset($params->name)?$params->name:null);
        $surname = (!is_null($json) && isset($params->surname)?$params->surname:null);
        $nit = (!is_null($json) && isset($params->nit)?$params->nit:null);
        $imageUrl = (!is_null($json) && isset($params->imageUrl)?$params->imageUrl:null);
        $idProgram = (!is_null($json) && isset($params->idProgram)?$params->idProgram:null);
        $idRol = (!is_null($json) && isset($params->idRol)?$params->idRol:null);
        $password = (!is_null($json) && isset($params->password)?$params->password:null);
    
        if(!is_null($json) && !is_null($email) && !is_null($name)  && !is_null($surname)  && !is_null($idRol) && !is_null($nit) && !is_null($imageUrl)   && !is_null($password) ){
            //Create User 
            try{
                $user = new User();
                $user->email = $email;
                $user->name = $name;
                $user->surname = $surname;
                $user->id_program = $idProgram;
                $user->id_rol = $idRol;
                $user->nit = $nit;
                $user->image_url = $imageUrl;
                $password = hash('sha256',$password);
                $user->password = $password;
                $isset_user = User::where('email','=',$email)->first();
                if(is_null($isset_user)){
                    $user->save();
                    $data = array(
                        'status' => 'ok',
                        'code' => 200,
                        'message' => 'User created'
                    );
                }else{
                    $data = array(
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'The user already exists'
                    );
                }
            }catch (Exception $e){
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'An error occurred :'." ".$e->getMessage()."\n"."in".$e->getFile()." and in line".$e->getLine()
                );
            }
            
        }else{
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'User not created, missing fields'
            );
            
        }
        return response()->json($data,200);
    }
    public function login(Request $request){
        $jwtAuth = new JwtAuth();
        $json = $request->input('json',null);
        $params = json_decode($json);
        $email = (!is_null($json) && isset($params->email))?$params->email:null;
        $password = (!is_null($json) && isset($params->password)?$params->password:null);
        $getToken = (!is_null($json) && isset($params->getToken))?$params->getToken:null;
        $password = hash('sha256',$password);
        if(!is_null($email) && !is_null($password) && (is_null($getToken) || $getToken == "false" )){
            $data = $jwtAuth->singup($email,$password);
        }elseif(!is_null($getToken)){
            $data = $jwtAuth->singup($email,$password,$getToken);
        }else{
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'User not login'
            );
        }
        return response()->json($data,200);
    }
}
