<?php
use FilmsOnYoutube\Models\Film\Film;
use Illuminate\Http\Response;


Route::group(['prefix' => 'api/v1'], function () {
	Route::resource('links', 'LinksController');
	Route::resource('sidebar', 'SidebarController');
});

// Posters
Route::get('poster/{film_id}', function($film_id){
	$film = Film::findOrFail($film_id);

	return response()->download($film->poster);
});
// If they land on a movie
Route::get('{id}/{name}', function ($id, $name) {
	return view('index');
})->where(['id' => '[0-9]+']);

Route::get('/', function () {
	return view('index');
});