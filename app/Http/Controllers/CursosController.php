<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Helpers\JwtAuth;
use App\Helpers\Validations;
class CursosController extends Controller
{
    public function getCursos(Request $request){
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checktoken = $jwtAuth->checkToken($hash);
        $validateService = new Validations();
        if($checktoken){
            $json = $request->input('json',null);   
            $params = json_decode($json);
            $params_array = json_decode($json,true);
            $programs = Program::all();
            if(is_object($programs)){
                $data = array(
                    'status' => 'ok',
                    'code' => 200,
                    'programs' => $programs
                ); 
            }else{
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'There are no records in the database'
                );
            }
        }
        
        return response()->json($data,200);
    }

}
