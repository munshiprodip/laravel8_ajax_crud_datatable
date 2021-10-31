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

Route::get('/', 'App\Http\Controllers\ContactController@index')->name("home");
Route::get('/contactApi', 'App\Http\Controllers\ContactController@contactApi')->name("contactApi");
Route::post('/contact-store', 'App\Http\Controllers\ContactController@store')->name("contact.store");
Route::get('/contact/{id}', 'App\Http\Controllers\ContactController@edit')->name("contact.edit");
Route::patch('/contact/update/{id}', 'App\Http\Controllers\ContactController@update')->name("contact.update");
Route::delete('/contact/delete/{id}', 'App\Http\Controllers\ContactController@destroy')->name("contact.destroy");
