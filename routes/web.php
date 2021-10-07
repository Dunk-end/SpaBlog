<?php

use Illuminate\Support\Facades\Route;

Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'create']);

Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);

Route::get('/getUserRole', [App\Http\Controllers\Auth\RegisterController::class, 'getUserRole']);

Route::get('/showCategory', [App\Http\Controllers\PostController::class, 'showCategory']);
//Route::get('/getPostCat', [App\Http\Controllers\PostController::class, 'getPostCat']);

//Route::get('/google', [App\Http\Controllers\Auth\RegisterController::class, 'googleRedirect']);
//Route::post('/google/reg', [App\Http\Controllers\Auth\RegisterController::class, 'loginWithGoogle']);

Route::any('{all?}', function () {

    return view('app');
})->where(['all' => '.*']);
