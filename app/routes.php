<?php

use App\controllers\postcontroller;
use App\controllers\commentcontroller;
use App\controllers\usercontroller;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::get('/', function()
{
	return View::make('hello');
});


      //                user login             //
Route::post('/user/','usercontroller@store');
Route::put('assigndepartment/','usercontroller@assign');
Route::post('status/','usercontroller@status');
Route::get('/user/','usercontroller@index');
Route::delete('user/{id}','usercontroller@destroy');
Route::get('user/{id}','usercontroller@show');
Route::put('user/{id}','usercontroller@update');
Route::post('login/','usercontroller@login');
Route::post('userprofile/','usercontroller@status');


    //             posts                     //
Route::group(['before' => 'oauth'], function()
    {
        Route::post('post/','postcontroller@store');
        Route::get('post/','postcontroller@index');
        Route::delete('post/{id}','postcontroller@delete');
        Route::get('post/{id}','postcontroller@show');
        Route::put('post/{id}','postcontroller@update');
        Route::post('postfav/','postcontroller@favourites');



        Route::post('/comment/','commentcontroller@store');
        Route::get('/comment/','commentcontroller@index');
        Route::delete('comment/{id}','commentcontroller@delete');
        Route::get('comment/{id}','commentcontroller@show');
        Route::put('comment/{id}','commentcontroller@update');




        Route::post('uploadProfile/','userprofilecontroller@create');
        Route::get('/userprofile/','userprofilecontroller@profile');

    });

   //              comments                 //
