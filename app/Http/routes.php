<?php
Route::group(['prefix' => 'api/v1'], function () {
	Route::resource('links', 'LinksController');
	Route::resource('sidebar', 'SidebarController');
});

Route::get('/', function () {
	return view('index');
});
// If they land on a movie
Route::get('{id}/{name}', function ($id, $name) {
	return view('index');
})
->where(['id' => '[0-9]+');