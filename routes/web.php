<?php

use App\Http\Controllers\Main\IndexController as IndexController;
//use App\Http\Controllers\Admin\Main\IndexController as IndexController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'App\Http\Controllers\Main'], function () {
    Route::get('/', IndexController::class);
});

Route::group(['namespace' => 'App\Http\Controller\Admin', 'prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::group(['namespace' => '\App\Http\Controllers\Admin\Main'], function () {
        Route::get('/', \App\Http\Controllers\Admin\Main\IndexController::class)->name('admin.main.index');
    });

    Route::group(['namespace' => '\App\Http\Controllers\Admin\Comment', 'prefix' => 'comments'], function () {
        Route::get('/', \App\Http\Controllers\Admin\Comment\IndexController::class)->name('admin.comment.index');
    });
});


Auth::routes();
