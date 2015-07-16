<?php
Route::group(['prefix' => 'api/v1'], function () {
	Route::resource('links', 'LinksController');
	Route::resource('sidebar', 'SidebarController');
});

Route::get('/', function () {
	return view('index');
});
