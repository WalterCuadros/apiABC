<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Helpers\JwtAuth;
use App\Helpers\Validations;
class CursosController extends Controller
{
    public function getCursos(){
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
        return response()->json($data,200);
    }

}
