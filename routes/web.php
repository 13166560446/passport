<?php

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
Route::post('/index/index','User\IndexController@index');
Route::post('/index/login','User\IndexController@login');
Route::get('/index/userlist','User\IndexController@userlist');



Route::get('/index/check','User\IndexController@md5test');


