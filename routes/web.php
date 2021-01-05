<?php

use App\Http\Controllers\CustomerController;
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

Route::resource('customers',CustomerController::class);

Route::get('/getCustomers', [CustomerController::class, 'getCustomers'])->name('getCustomers');
//Route::get('/getCustomers', [CustomerController::class, 'getCustomers']);

//Route::get('/customers','CustomerController@index');
//Route::get('/customers/getCustomers/','CustomerController@getCustomers')->name('customers.getCustomers');
