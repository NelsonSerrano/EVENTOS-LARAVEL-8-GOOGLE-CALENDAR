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

Route::get('/', function () {
    return view('welcome');
});


Route::get('crear', 'gCalendarController@create');
Route::get('actualizar', 'gCalendarController@actualizar');
Route::get('eliminar', 'gCalendarController@eliminar');

Route::put('actualizar-evento/{id}', 'gCalendarController@actualizarEvent');

// Route::resource('cal', 'gCalendarController');
Route::get('oauth', 'gCalendarController@oauth');


