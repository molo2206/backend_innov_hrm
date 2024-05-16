<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Authentification
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/editpassword', [AuthController::class, 'editPassword']);
Route::post('auth/checkemail', [AuthController::class, 'checkemail']);

Route::group(['middleware' => ['auth:sanctum',]], function () {
    //Gestion de l'authentification
    Route::post('auth/editPhoto', [AuthController::class, 'editPhoto']);
    Route::post('auth/updateuser', [AuthController::class, 'update']);
    //Gestion des utilisateurs
    Route::post('user/store', [UserController::class, 'store']);
    Route::put('user/update/{id}', [UserController::class, 'update']);
    Route::get('user/index', [UserController::class, 'index']);
    Route::delete('user/delete/{id}', [UserController::class, 'destroy']);
    Route::get('user/status/{id}', [UserController::class, 'status']);
    Route::post('user/editimage/{id}', [UserController::class,'editPhoto']);
    //Gestion des company
    Route::post('company/store',[CompanyController::class, 'store']);

});
