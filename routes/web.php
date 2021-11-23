<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DiagnosticoController;
use App\Http\Controllers\CursosController;
use Illuminate\Support\Facades\DB;
use App\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('/api/registerUser',[UserController::class, 'register']);
Route::post('/api/loginUser',[UserController::class, 'login']);
Route::post('/api/autodiagnosticoById',[DiagnosticoController::class, 'autodiagnosticoById']);
Route::middleware('api.auth')->resource('/api/autodiagnostico', DiagnosticoController::class);
Route::middleware('api.auth')->get('/api/getPrograms',[CursosController::class, 'getCursos']);
Route::middleware('api.auth')->get('/api/getDiagnosticosByProgram/{id_program}',[DiagnosticoController::class, 'getDiagnosticosByIdCurso']);