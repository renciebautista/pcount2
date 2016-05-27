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

Route::get('time', function(){
	$stores = App\Models\StoreUser::all()->store()->get();
	dd($stores->count());

});


// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', ['as' => 'auth.dologin', 'uses' =>  'Auth\AuthController@postLogin']);
Route::get('auth/logout', ['as' => 'auth.logout', 'uses' =>  'Auth\AuthController@getLogout']);


Route::get('latest/{filename}', 'ApkController@latest');
Route::get('testspeed', function(){
	return view('testspeed');
});

Route::group(['middleware' => 'auth'], function () {

	Route::group(['middleware' => ['role:admin']], function () {

		Route::get('import/masterfile', ['as' => 'import.masterfile', 'uses' => 'ImportController@masterfile']);
		Route::post('import/masterfileuplaod', ['as' => 'import.masterfileuplaod', 'uses' => 'ImportController@masterfileuplaod']);

		Route::get('export/stores', ['as' => 'export.stores', 'uses' => 'ExportController@stores']);
		Route::get('export/items', ['as' => 'export.items', 'uses' => 'ExportController@items']);
		Route::get('export/othercode', ['as' => 'export.othercode', 'uses' => 'ExportController@othercode']);
		Route::get('export/storeitems', ['as' => 'export.storeitems', 'uses' => 'ExportController@storeitems']);
		Route::get('export/storeosa', ['as' => 'export.storeosa', 'uses' => 'ExportController@storeosa']);
		Route::get('export/storeassortment', ['as' => 'export.storeassortment', 'uses' => 'ExportController@storeassortment']);

		Route::get('store/invalid', array('as' => 'store.invalid', 'uses' => 'StoreController@invalid'));
		Route::get('store/{id}/mkl', 'StoreController@mkl');
		Route::get('store/{id}/assortment', 'StoreController@assortment');
		Route::resource('store', 'StoreController');

		Route::get('item/removeig', array('as' => 'item.removeig', 'uses' => 'ItemController@removeig'));
		Route::post('item/removeig', array('as' => 'item.postremoveig', 'uses' => 'ItemController@postremoveig'));
		Route::get('item/updateig', array('as' => 'item.updateig', 'uses' => 'ItemController@updateig'));
		Route::post('item/updateig', array('as' => 'item.postupdateig', 'uses' => 'ItemController@postupdateig'));
		Route::get('item/updatedig', array('as' => 'item.updatedig', 'uses' => 'ItemController@updatedig'));
		Route::get('item/downloadupdatedig', array('as' => 'item.downloadupdatedig', 'uses' => 'ItemController@downloadupdatedig'));
		Route::get('item/{id}/othercode', 'ItemController@othercode');
		Route::resource('item', 'ItemController');
		Route::post('item', array('as' => 'item.postItemType', 'uses' => 'ItemController@postItemType'));

		Route::get('store_user/{id}/store', 'StoreUserController@storelist');
		Route::get('store_user/{id}/changepassword', array('as' => 'store_user.changepassword', 'uses' => 'StoreUserController@changepassword'));
		Route::put('store_user/{id}/postupdate', array('as' => 'store_user.postupdate', 'uses' => 'StoreUserController@postupdate'));
		Route::resource('store_user', 'StoreUserController');
		
		Route::get('device_user/{id}', 'DeviceUserController@logOut');
		Route::resource('device_users', 'DeviceUserController');

		Route::resource('settings', 'SettingsController', [
		    'only' => ['index', 'store']
		]);

		Route::resource('apk', 'ApkController');
	    Route::resource('testapk', 'TestApkController');

	    Route::resource('roles', 'RoleController');
	    Route::resource('devices', 'DeviceController');

	    Route::get('mapping/invalid', array('as' => 'mapping.invalid', 'uses' => 'InvalidMappingController@invalid'));

	    Route::get('history/posting', array('as' => 'history.posting', 'uses' => 'HistoryController@posting'));
	    Route::post('history/posting', array('as' => 'history.postposting', 'uses' => 'HistoryController@postposting'));

	});

    Route::get('/', ['as' => 'dashboard.index', 'uses' => 'DashboardController@index']);

	Route::get('/dashboard', ['as' => 'dashboard.index', 'uses' => 'DashboardController@index']);

	// Route::resource('agency', 'AgencyController');

	

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

	Route::resource('compliance', 'ComplianceReportController', [
	    'only' => ['index', 'store']
	]);
});


Route::group(array('prefix' => 'api'), function()
{
	Route::get('auth', 'Api\AuthUserController@auth');
	Route::get('logout', 'Api\AuthUserController@logout');
	Route::get('download', 'Api\DownloadController@index');

	Route::post('uploadpcount', 'Api\UploadController@uploadpcount');
	Route::post('uploadimage', 'Api\UploadController@uploadimage'); 
	Route::get('pcountimage/{name}', 'Api\DownloadController@image'); 

	Route::post('uploadassortment', 'Api\UploadAssortmentController@uploadassortment');
	Route::post('uploadassortmentimage', 'Api\UploadAssortmentController@uploadimage');   
	Route::get('assortmentimage/{name}', 'Api\DownloadController@assortmentimage');

	Route::post('clientlist', array('as' => 'clientlist', 'uses' => 'Api\FilterController@clientlist'));
	Route::post('channellist', array('as' => 'channellist', 'uses' => 'Api\FilterController@channellist'));
	Route::post('distributorlist', array('as' => 'distributorlist', 'uses' => 'Api\FilterController@distributorlist'));
	Route::post('enrollmentlist', array('as' => 'enrollmentlist', 'uses' => 'Api\FilterController@enrollmentlist'));
	Route::post('regionlist', array('as' => 'regionlist', 'uses' => 'Api\FilterController@regionlist'));
	Route::post('storelist', array('as' => 'storelist', 'uses' => 'Api\FilterController@storelist'));
	Route::post('areastorelist', array('as' => 'areastorelist', 'uses' => 'Api\FilterController@areastorelist'));

	Route::post('allareastorelist', array('as' => 'allareastorelist', 'uses' => 'ComplianceReportController@allareastorelist'));

	Route::post('categorylist', array('as' => 'categorylist', 'uses' => 'Api\FilterController@categorylist'));
	Route::post('subcategorylist', array('as' => 'subcategorylist', 'uses' => 'Api\FilterController@subcategorylist'));
	Route::post('brandlist', array('as' => 'brandlist', 'uses' => 'Api\FilterController@brandlist'));

	Route::get('prnlist', 'Api\DownloadController@prnlist');
	Route::get('downloadprn/{filename}', 'Api\DownloadController@downloadprn');

	Route::get('protected/{token}/{file_name}', 'Api\UpdateapkController@download');
	Route::post('check', 'Api\CheckupdateController@check');
	Route::post('verify', 'Api\CheckupdateController@verify');

	Route::get('betaprotected/{token}/{file_name}', 'Api\UpdateapkController@betadownload');
	Route::post('betacheck', 'Api\CheckupdateController@betacheck');
	Route::post('betaverify', 'Api\CheckupdateController@betaverify');

});
