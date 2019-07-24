<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('home');
});

// RESTful API
// Route::resource('user', 'UserController', ['only' => [
//     'index', 'edit', 'update', 'store', 'destroy'
// ]]);

Route::group(['prefix' => '/user'], function () {

    // Route::get('/{id}', 'UserController@show');

    Route::get('/', 'UserController@index');

    Route::post('/', 'UserController@store');

    Route::get('/edit/{id}', 'UserController@edit');

    Route::put('/update/{id}', 'UserController@update');

    Route::delete('/del/{id}', 'UserController@destroy');

    Route::get('/check_email/{email}', 'UserController@checkEmailExist');

    Route::get('/check_name/{name}', 'UserController@checkNameExist');

    Route::post('/account', 'UserController@checkAccount');

});

Route::group(['prefix' => '/file'], function () {

    // Route::get('/{id}', 'UserController@show');

    Route::get('/', 'FileController@index');

    Route::post('/', 'FileController@store');

    Route::get('/edit/{id}', 'FileController@edit');

    Route::put('/update/{id}', 'FileController@update');

    Route::delete('/del/{id}', 'FileController@destroy');

    Route::post('/avatar', 'FileController@storeAvatar');

});
