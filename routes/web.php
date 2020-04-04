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

Route::get('/vesta_form', function () {
    return view('vesta_form');
});

Route::post('/vesta_form', 'UploadFileController@store');
Route::get('/start_analysis', 'DataController@startAnalysis');
