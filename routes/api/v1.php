<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::resource('users', 'UsersController');


Route::get('/ping', function (Request $request) {
    return response()->json(\Carbon\Carbon::now());
});

Route::middleware('api')->post('auth/login', 'AuthController@loginAction');
Route::middleware('api')->post('auth/logout', 'AuthController@logoutAction');
Route::middleware('api')->post('auth/register', 'AuthController@registerAction');

// Verify user email address.
Route::middleware('api')->get('auth/verify/{userToken}', ['as' =>'user.register.verify' ,'uses' =>'AuthController@registerUserVerifyAction']);

//Route::middleware('api')->get('auth/reset/password', ['as' =>'user.reset.password' ,'uses' =>'AuthController@resetPasswordAction']);
Route::middleware('api')->post('auth/reset', ['as' =>'user.password.reset' ,'uses' =>'AuthController@resetPasswordVerifyAction']);
