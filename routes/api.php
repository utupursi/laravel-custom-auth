<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('token')->group(function () {
    Route::get('user', function () {
        return response()->json(1);
    })->name('user');
    Route::post('create-access-token',[\App\Http\Controllers\AccessTokenController::class,'create']);
    Route::post('delete-access-token',[\App\Http\Controllers\AccessTokenController::class,'delete']);
});

Route::post('/verifyToken', [\App\Http\Controllers\AuthController::class, 'verifyToken']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
