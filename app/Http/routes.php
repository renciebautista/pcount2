<?php

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
    return view('welcome');
});

Route::get('/dashboard', ['as' => 'dashboard.index', 'uses' => 'DashboardController@index']);

Route::resource('agency', 'AgencyController');

Route::get('import/masterfile', ['as' => 'import.masterfile', 'uses' => 'ImportController@masterfile']);
Route::post('import/masterfileuplaod', ['as' => 'import.masterfileuplaod', 'uses' => 'ImportController@masterfileuplaod']);

Route::group(array('prefix' => 'api'), function()
{
	Route::get('auth', 'Api\AuthUserController@auth');
	Route::get('download', 'Api\DownloadController@index');

	Route::get('uploadpcount', 'Api\UploadController@uploadpcount');
	Route::post('uploadimage', 'Api\UploadController@uploadimage');   
	Route::get('image/{name}', 'Api\DownloadController@image');
});
