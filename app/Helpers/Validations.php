<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class Validations{
    private $rulesSetAutodiagnostico = array(       
        'pregunta_1'=> 'required',
        'pregunta_2'=> 'required'
    );
    private $rulesSetIdCurso = array(
        'id_curso'=> 'required|numeric|min:1'
    );
    public function validate($params_array, $rule){
        $error = false;
        switch ($rule) {
            case 'setautodiagnostico':
                $validate = Validator::make($params_array,$this->rulesSetAutodiagnostico);
                if($validate->fails()){
                    $error = $validate->errors();
                }
                break;
            case 'setIdCurso':
                $validate = Validator::make($params_array,$this->rulesSetIdCurso);
                if($validate->fails()){
                    $error = $validate->errors();
                }
                break; 
        }
        return $error;
    }
}