<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Models\User;
use App\Models\Autodiagnostico;
use App\Models\Estado;
use App\Helpers\Validations;
use Illuminate\Support\Facades\DB;

class DiagnosticoController extends Controller
{

  

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jwtAuth = new JwtAuth();
        $checktoken = $jwtAuth->checkToken($request->header('Authorization', null));
        if($checktoken){

            $data = array(
                'status' => 'ok',
                'code' => 200,
                'message' => 'User  authorized'
            );
        }else{
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'User not authorized'
            );
        }
        return response()->json($data,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $validateService = new Validations();
        $opccionesReferencia = array('a','b','c','d','e','f','g');
        $json = $request->input('json',null);   
        $params = json_decode($json);
        $params_array = json_decode($json,true);
        $validate = $validateService->validate($params_array,'setautodiagnostico');
        if($validate){
            return response()->json($validate,400);
        }
        $user =  $jwtAuth->checkToken($hash,true);
        $autodiagnostico = new Autodiagnostico();
        $autodiagnostico->id_user = $user->sub;
        $opcionesPregunta2 = str_split($params->pregunta_2);
        $contadorOpciones = 0;
        for ($i=0; $i <=count($opcionesPregunta2)-1 ; $i++) { 
            if(in_array($opcionesPregunta2[$i],$opccionesReferencia)) {
                $contadorOpciones++;
            }
        }
        $estado = ($contadorOpciones>=1)?2:1;
        $autodiagnostico->id_estado = $estado;
        $autodiagnostico->pregunta_1 = $params->pregunta_1;
        $autodiagnostico->pregunta_2 = $params->pregunta_2;
        $autodiagnostico->fecha =   strtotime(date('d-m-Y'));
        $autodiagnostico->save();
        $data = array(
            'status' => 'ok',
            'code' => 200,
            'message' => 'Record saved'
        );
        return response()->json($data,$data['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_user)
    {
        $user =  DB::table('users')->select('users.id','users.name','users.surname','users.nit','users.email','users.image_url','programs.name_program')
            ->join('programs', 'users.id_program', '=', 'programs.id')
            ->where('users.id',$id_user)
            ->orderByRaw('updated_at - created_at DESC')->first();
            if(is_object($user))
            {
                $autodiagnosticoByUser = DB::table('autodiagnosticos')
                ->join('estados', 'autodiagnosticos.id_estado', '=', 'estados.id')
                ->select('autodiagnosticos.id as id_auto','autodiagnosticos.fecha','estados.name_estado as estado')
                ->where('autodiagnosticos.id_user',$id_user)
                ->where('autodiagnosticos.fecha', strtotime(date('d-m-Y')))
                ->orderByRaw('updated_at - created_at DESC')
                ->get()->first();
                $id = $user->id;
                $name = $user->name;
                $surname = $user->surname;
                $program = $user->name_program;
                $nit = $user->nit;
                $email = $user->email;
                $image = $user->image_url;
                if(is_object($autodiagnosticoByUser)){  
                    $date = $autodiagnosticoByUser->fecha;
                    $date = date('Y-m-d',$date);
                    $state = $autodiagnosticoByUser->estado;
                    $id_autodiagnostico = $autodiagnosticoByUser->id_auto;
                }else{
                   $date = "N/A";
                   $state = "NO HAY REGISTROS";
                }
                $data = array(
                    'status' => 'ok',
                    'code' => 200,
                    'data'=>array(
                        'id_user'=>$id,
                        'name'=>$name,
                        'surname'=>$surname,
                        'program'=>$program,
                        'nit'=> $nit,
                        'email'=> $email,
                        'image'=>$image,
                        'date'=>$date,
                        'state'=>$state,
                        'id'=>$id_autodiagnostico
                        )
                );
                
            }else{
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'User no register'
                );
            }
            return response()->json($data,$data['code']);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $json = $request->input('json',null);   
        $params = json_decode($json);
        $params_array = json_decode($json,true);
        $validateService = new Validations();
        $opccionesReferencia = array('a','b','c','d','e','f','g');
        $validate = $validateService->validate($params_array,'setautodiagnostico');
        if($validate){
            return response()->json($validate,400);
        }
        $autodiagnostico = Autodiagnostico::find($id);
        $opcionesPregunta2 = str_split($params->pregunta_2);
        $contadorOpciones = 0;
        for ($i=0; $i <=count($opcionesPregunta2)-1 ; $i++) { 
            if(in_array($opcionesPregunta2[$i],$opccionesReferencia)) {
                $contadorOpciones++;
            }
        }
        $estado = ($contadorOpciones>=1)?2:1;
        $autodiagnostico->id_estado = $estado;
        $autodiagnostico->pregunta_1 = $params->pregunta_1;
        $autodiagnostico->pregunta_2 = $params->pregunta_2;
        $autodiagnostico->fecha =   strtotime(date('d-m-Y'));
        $autodiagnostico->save();
        $data = array(
            'status' => 'ok',
            'code' => 200,
            'message' => 'Record update'
        );
        return response()->json($data,$data['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDiagnosticosByIdCurso($id_program){
    
        $users = DB::table('users')
            ->select('users.id as id_user' ,'users.name','users.surname',DB::raw('IFNULL(estados.name_estado, "SIN REGISTRO") as estado') )
            ->leftjoin('autodiagnosticos', 'users.id', '=', 'autodiagnosticos.id_user' )
            ->leftjoin('estados', 'autodiagnosticos.id_estado', '=', 'estados.id')
            ->where('id_program', $id_program)->get();
        if(is_object($users) && !$users->isEmpty()){
            $data = array(
                'status' => 'ok',
                'code' => 200,
                'estudents' => $users
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
