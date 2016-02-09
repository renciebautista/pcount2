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
use App\Models\TempInventories;

Route::get('test', function(){
	// $folderpath = base_path().'/database/seeds/seed_files/'.date('mdY');
	// echo $folderpath;
	// if (!File::exists($folderpath))
	// {
	// 	File::makeDirectory($folderpath);
	// }
	$item = TempInventories::where('store_inventory_id', 73)
                    ->where('other_barcode', '80200442')
                    ->first();
    dd($item);
});

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', ['as' => 'auth.dologin', 'uses' =>  'Auth\AuthController@postLogin']);
Route::get('auth/logout', ['as' => 'auth.logout', 'uses' =>  'Auth\AuthController@getLogout']);


Route::group(['middleware' => 'auth'], function () {
    Route::get('/', ['as' => 'dashboard.index', 'uses' => 'DashboardController@index']);

	Route::get('/dashboard', ['as' => 'dashboard.index', 'uses' => 'DashboardController@index']);

	Route::resource('agency', 'AgencyController');

	Route::get('import/masterfile', ['as' => 'import.masterfile', 'uses' => 'ImportController@masterfile']);
	Route::post('import/masterfileuplaod', ['as' => 'import.masterfileuplaod', 'uses' => 'ImportController@masterfileuplaod']);


	Route::get('store/{id}/items', 'StoreController@items');
	Route::resource('store', 'StoreController');

	Route::get('item/{id}/othercode', 'ItemController@othercode');
	Route::resource('item', 'ItemController');


	Route::get('inventory', array('as' => 'inventory.index', 'uses' => 'InventoryController@index'));
	Route::post('inventory', array('as' => 'inventory.show', 'uses' => 'InventoryController@store'));


	Route::get('so/area', array('as' => 'so.area', 'uses' => 'SalesOrderController@area'));
	Route::post('so/area', array('as' => 'so.postarea', 'uses' => 'SalesOrderController@postarea'));
	Route::get('so/store', array('as' => 'so.store', 'uses' => 'SalesOrderController@store'));
	Route::post('so/store', array('as' => 'so.poststore', 'uses' => 'SalesOrderController@poststore'));

});


Route::group(array('prefix' => 'api'), function()
{
	Route::get('auth', 'Api\AuthUserController@auth');
	Route::get('download', 'Api\DownloadController@index');

	Route::post('uploadpcount', 'Api\UploadController@uploadpcount');
	Route::post('uploadimage', 'Api\UploadController@uploadimage');   
	Route::get('image/{name}', 'Api\DownloadController@image');

	Route::post('clientlist', array('as' => 'clientlist', 'uses' => 'Api\FilterController@clientlist'));
	Route::post('channellist', array('as' => 'channellist', 'uses' => 'Api\FilterController@channellist'));
	Route::post('distributorlist', array('as' => 'distributorlist', 'uses' => 'Api\FilterController@distributorlist'));
	Route::post('enrollmentlist', array('as' => 'enrollmentlist', 'uses' => 'Api\FilterController@enrollmentlist'));
	Route::post('regionlist', array('as' => 'regionlist', 'uses' => 'Api\FilterController@regionlist'));
	Route::post('storelist', array('as' => 'storelist', 'uses' => 'Api\FilterController@storelist'));
	Route::post('areastorelist', array('as' => 'areastorelist', 'uses' => 'Api\FilterController@areastorelist'));

	Route::post('categorylist', array('as' => 'categorylist', 'uses' => 'Api\FilterController@categorylist'));
	Route::post('subcategorylist', array('as' => 'subcategorylist', 'uses' => 'Api\FilterController@subcategorylist'));
	Route::post('brandlist', array('as' => 'brandlist', 'uses' => 'Api\FilterController@brandlist'));
});
