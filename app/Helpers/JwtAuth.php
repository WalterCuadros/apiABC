<?php
namespace App\Helpers;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Constant;

class JwtAuth{
    private  function getKeyToken(){
        $constant =  Constant::where(
            array(
                'name_constant'=> 'keyToken'
            )
        )->first();
        return $constant->content;
    }

    public function singup($email, $password, $getToken =null){
        
        $user =  User::where(
            array(
                'email'=>$email,
                'password'=>$password

            )
        )->first();
        $signup= false;
        if(is_object($user)){
            $signup = true;

        }
        if($signup){
            $token = array(
                'sub' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'surname' => $user->surname,
                'iat' => time(),
                'exp' => time()+(7*24*60*60)
            );
            $key = $this->getKeyToken();
            $jwt = JWT::encode($token,$key,'HS256');
            $decoded = JWT::decode($jwt,$key,array('HS256'));
            $data = array(
                'status' => 'ok',
                'code' => 200
            );
            if(!is_null($getToken)){
                $data['token'] =  $jwt;
            }else{
                $data['identity'] =  $decoded;
                $data['token'] =  $jwt;
            }
        }else{  
            $data['status'] = 'error';
            $data['code'] =  400;
            $data['message'] = 'Unauthorized client error';
        }
        return $data;

    }
    public function checkToken($jwt, $getIdentity = null){
        $auth = false;
        try {
            $key = $this->getKeyToken();
            $decoded = JWT::decode($jwt,$key,array('HS256'));
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        }catch (\DomainException $e){
            $auth = false;
        }
        if(is_object($decoded) && isset($decoded->sub)){
            $auth = true;
        }else{
            $auth = false;
        }

        if($getIdentity){
            return $decoded;
        }
        return $auth;
    }
}
