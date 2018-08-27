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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/mailable', 'TestController@mailable');
Route::post('/stripe/webhook','WebhookController@handleWebhook');
Route::post('/braintree/webhook','WebhookController@handleWebhook');

Route::resource('todo','TodosController');