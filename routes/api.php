<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* 
    api/v1/route1
    api/v1/route2
*/


Route::prefix('v1')->group(function(){



    Route::post('/login');
    Route::post('/register');
    Route::post('/logout');
});


Route::fallback(function(){
    
});
