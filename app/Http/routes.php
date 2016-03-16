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

	Route::get('store/{id}/mkl', 'StoreController@mkl');
	Route::get('store/{id}/assortment', 'StoreController@assortment');
	Route::resource('store', 'StoreController');

	Route::get('item/{id}/othercode', 'ItemController@othercode');
	Route::resource('item', 'ItemController');
	Route::post('item', array('as' => 'item.postItemType', 'uses' => 'ItemController@postItemType'));

	Route::get('store_user/{id}/store', 'StoreUserController@storelist');
	Route::resource('store_user', 'StoreUserController');

	Route::get('inventory', array('as' => 'inventory.index', 'uses' => 'InventoryController@index'));
	Route::post('inventory', array('as' => 'inventory.show', 'uses' => 'InventoryController@store'));
	Route::get('inventory/{type}', array('as' => 'inventory.index', 'uses' => 'InventoryController@index'));
	Route::post('inventory/{type}', array('as' => 'inventory.show', 'uses' => 'InventoryController@store'));

	Route::get('so/area/{type}', array('as' => 'so.area', 'uses' => 'SalesOrderController@area'));
	Route::post('so/area/{type}', array('as' => 'so.postarea', 'uses' => 'SalesOrderController@postarea'));
	Route::get('so/store/{type}', array('as' => 'so.store', 'uses' => 'SalesOrderController@store'));
	Route::post('so/store/{type}', array('as' => 'so.poststore', 'uses' => 'SalesOrderController@poststore'));
	Route::get('osa/area/{type}', array('as' => 'osa.area', 'uses' => 'OsaController@area'));
	Route::post('osa/area/{type}', array('as' => 'osa.postarea', 'uses' => 'OsaController@postarea'));
	Route::get('osa/store/{type}', array('as' => 'osa.store', 'uses' => 'OsaController@store'));
	Route::post('osa/store/{type}', array('as' => 'osa.poststore', 'uses' => 'OsaController@poststore'));
	Route::get('oos/sku/{type}', array('as' => 'oos.sku', 'uses' => 'OutofstockController@sku'));
	Route::post('oos/sku/{type}', array('as' => 'oos.postsku', 'uses' => 'OutofstockController@postsku'));

	Route::resource('assortment', 'AssortmentController', [
	    'only' => ['index', 'store']
	]);

});


Route::group(array('prefix' => 'api'), function()
{
	Route::get('auth', 'Api\AuthUserController@auth');
	Route::get('download', 'Api\DownloadController@index');

	Route::post('uploadpcount', 'Api\UploadController@uploadpcount');
	Route::post('uploadimage', 'Api\UploadController@uploadimage');  
	Route::post('uploadassortment', 'Api\UploadAssortmentController@uploadassortment');
	Route::post('uploadassortmentimage', 'Api\UploadAssortmentController@uploadimage');   
	Route::get('pcountimage/{name}', 'Api\DownloadController@image');
	Route::get('assortmentimage/{name}', 'Api\DownloadController@assortmentimage');

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
