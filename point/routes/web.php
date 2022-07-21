<?php

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

Route::get('/', 'App\Http\Controllers\UserController@index')->name('index');
Route::get('/index', 'App\Http\Controllers\UserController@index');

Route::get('/login', function () {
    return view('login');
})->name('login');
Route::get('/login_admin', function () {
    return view('login_admin');
});
Route::post('/auth', 'App\Http\Controllers\UserController@auth')->name('auth');
Route::post('/auth_admin', 'App\Http\Controllers\UserController@auth_admin')->name('auth_admin');
Route::get('/logout', 'App\Http\Controllers\UserController@logout')->name('logout');
Route::get('/session_out', 'App\Http\Controllers\UserController@session_out')->name('session_out');

Route::post('/attendance', 'App\Http\Controllers\UserController@attendance')->name('attendance');
Route::post('/leaving', 'App\Http\Controllers\UserController@leaving')->name('leaving');

Route::get('/user_info', 'App\Http\Controllers\UserController@user_info')->name('user_info');
Route::get('/pass_change', 'App\Http\Controllers\UserController@pass_change')->name('pass_change');
Route::post('/pass_change_check', 'App\Http\Controllers\UserController@pass_change_check')->name('pass_change_check');
Route::get('/pass_change_complete', 'App\Http\Controllers\UserController@pass_change_complete')->name('pass_change_complete');

Route::get('/user_admin', 'App\Http\Controllers\UserController@user_admin')->name('user_admin');
Route::get('/user_admin_search', 'App\Http\Controllers\UserController@user_admin_search');
Route::get('/add_user', 'App\Http\Controllers\UserController@add_user')->name('add_user');
Route::get('/add_user_confirm', 'App\Http\Controllers\UserController@add_user_confirm');
Route::post('/add_user_confirm', 'App\Http\Controllers\UserController@add_user_confirm')->name('add_user_confirm');
Route::get('/add_user_complete', 'App\Http\Controllers\UserController@add_user_complete');
Route::post('/add_user_complete', 'App\Http\Controllers\UserController@add_user_complete')->name('add_user_complete');

Route::get('/edit_user', 'App\Http\Controllers\UserController@edit_user');
Route::post('/edit_user', 'App\Http\Controllers\UserController@edit_user')->name('edit_user');
Route::get('/edit_user_confirm', 'App\Http\Controllers\UserController@edit_user_confirm');
Route::post('/edit_user_confirm', 'App\Http\Controllers\UserController@edit_user_confirm')->name('edit_user_confirm');
Route::get('/edit_user_complete', 'App\Http\Controllers\UserController@edit_user_complete');
Route::post('/edit_user_complete', 'App\Http\Controllers\UserController@edit_user_complete')->name('edit_user_complete');
Route::get('/delete_user', 'App\Http\Controllers\UserController@delete_user');
Route::post('/delete_user', 'App\Http\Controllers\UserController@delete_user')->name('delete_user');

Route::get('/detail', 'App\Http\Controllers\UserController@detail')->name('detail');
Route::get('/edit_detail', 'App\Http\Controllers\UserController@edit_detail');
Route::post('/edit_detail', 'App\Http\Controllers\UserController@edit_detail')->name('edit_detail');
Route::get('/edit_detail_confirm', 'App\Http\Controllers\UserController@edit_detail_confirm');
Route::post('/edit_detail_confirm', 'App\Http\Controllers\UserController@edit_detail_confirm')->name('edit_detail_confirm');
Route::get('/edit_detail_complete', 'App\Http\Controllers\UserController@edit_detail_complete');
Route::post('/edit_detail_complete', 'App\Http\Controllers\UserController@edit_detail_complete')->name('edit_detail_complete');
Route::get('/delete_detail', 'App\Http\Controllers\UserController@delete_detail');
Route::post('/delete_detail', 'App\Http\Controllers\UserController@delete_detail')->name('delete_detail');

Route::get('/csv', 'App\Http\Controllers\UserController@csv');
Route::post('/csv', 'App\Http\Controllers\UserController@csv')->name('csv');

Route::get('/point', 'App\Http\Controllers\UserController@point')->name('point');
Route::get('/product', 'App\Http\Controllers\UserController@product')->name('product');
Route::get('/purchase', 'App\Http\Controllers\UserController@purchase');
Route::post('/purchase', 'App\Http\Controllers\UserController@purchase')->name('purchase');
Route::get('/purchase_complete', 'App\Http\Controllers\UserController@purchase_complete');
Route::post('/purchase_complete', 'App\Http\Controllers\UserController@purchase_complete')->name('purchase_complete');
