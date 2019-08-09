<?php

Route::get('/', 'PagesController@index')->name('home');
Route::get('/dashboard', 'DashboardController@index');
Route::get('/error01', 'PagesController@error01')->name('error01');

//**********ITEMS**************/
Route::get('items/{id}/issue', 'ItemsController@issue'); //additional method in ItemsController
Route::get('items/receivemulti', 'ItemsController@receiveMulti');
Route::post('items/receivemulti', 'ReceivingsController@storeMulti');
Route::get('items/issuemulti', 'ItemsController@issueMulti');
Route::post('items/issuemulti', 'IssuancesController@storeMulti');
Route::resource('items', 'ItemsController');
Route::get('getUom/{id}', 'ItemsController@getUom'); //get uom based on item_id
Route::get('getItemQuantity/{id}', 'ItemsController@getItemQuantity');
Route::get('getItems', 'ItemsController@getAll')->name('get.items'); //for ajax
//****************************/

//**********ISS/REC*****************/
Route::get('getIss', 'IssuancesController@getAll')->name('get.iss'); //for datatables ajax // send data to /getIss dt will then get data to /getIss via get.iss
Route::resource('issuances', 'IssuancesController');
Route::get('getRec', 'ReceivingsController@getAll')->name('get.rec'); //for datatables ajax
Route::resource('receivings', 'ReceivingsController');
//*********************************/

//**********ADMIN***************/
Auth::routes(); //Auth::routes(['register' => false]) //disable registration;
Route::get('admin/accounts', 'UsersController@index');
Route::post('admin/accounts', 'UsersController@update');
Route::get('admin/adminlogs', 'UsersController@adminLogs');
Route::get('getAdminLogs', 'UsersController@getAdminLogs')->name('get.adminLogs');
Route::get('/changepass', 'UsersController@changePass');
Route::post('/changepass', 'UsersController@changePassword');
Route::get('getUser', 'UsersController@getAll')->name('get.user');
//******************************/

//**********Excel***************/
Route::get('/excel', 'ExcelController@index');
Route::get('excel/exportItems', 'ExcelController@exportItems')->name('export.items');
Route::post('excel/importIssuances', 'ExcelController@importIssuances')->name('import.issuances');
//******************************/
