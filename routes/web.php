<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
//use App\Http\Controllers\Admin\Main\IndexController as IndexController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main\IndexController as IndexController;

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

Route::get('/', 'HomeController');

Route::group(['namespace' => 'App\Http\Controller\Upa', 'prefix' => 'upa', 'middleware' => ['auth']], function () {
    Route::group(['namespace' => '\App\Http\Controllers\Upa\Main'], function () {
        Route::get('/', \App\Http\Controllers\Upa\Main\IndexController::class)->name('upa.main.index');
    });

    Route::group(['namespace' => '\App\Http\Controllers\Upa\Comment', 'prefix' => 'comments'], function () {
        Route::get('/', \App\Http\Controllers\Upa\Comment\IndexController::class)->name('upa.comment.index');
    });
});


Auth::routes();
