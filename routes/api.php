<?php

use Illuminate\Http\Request; // ✅ Agrega esta línea
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tareas', TareaController::class);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tareas', 'TareaController@index');
    Route::post('/tareas', 'TareaController@store');
    Route::get('/tareas/{id}', 'TareaController@show');
    Route::patch('/tareas/{id}/estado', 'TareaController@actualizarEstado');
    Route::delete('/tareas/{id}', 'TareaController@destroy');
    Route::post('/tareas/{id}/comentarios', 'TareaController@agregarComentario');
});
