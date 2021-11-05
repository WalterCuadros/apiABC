<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autodiagnostico extends Model
{
    use HasFactory;
    public function user(){
        return $this->belongsTo('App\Models\User','id_user');
    }
    public function estado(){
        return $this->belongsTo('App\Models\Estado','id_estado');
    }
}
