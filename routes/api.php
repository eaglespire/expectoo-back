<?php

use App\Http\Controllers\Api\AccessController;
use App\Http\Controllers\Api\ContactController;
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

Route::middleware('auth:sanctum')->controller(ContactController::class)
    ->prefix('contacts')->group(function (){
    Route::get('/','index');
    Route::post('create','store');
    Route::put('update/{contact:slug}','update');
    Route::delete('delete/{contact:slug}','destroy');
});


Route::prefix('user')->group(function (){
    Route::controller(AccessController::class)->group(function (){
        Route::post('register', 'register' );
        Route::post('login', 'login');
    });
});

