<?php

namespace FilmsOnYoutube\Http\Controllers;

use Illuminate\Http\Request;

use FilmsOnYoutube\Http\Requests;
use FilmsOnYoutube\Http\Controllers\Controller;
use FilmsOnYoutube\Models\Film\Genre\Available as GenreAvailable;
use FilmsOnYoutube\Models\Film\Language\Available as LanguageAvailable;

use FilmsOnYoutube\Models\Link\Resolution\Available as ResolutionAvailable;

use FilmsOnYoutube\Models\Link\Link;
use FilmsOnYoutube\Models\Film\Film;

class SidebarController extends Controller
{

	public function index()
	{
		$sidebar = [];		

		/** Genres **/
		$sidebar['genres'] = GenreAvailable::orderBy('name', 'ASC')->has('genres')->get();
		/** Language **/
		$sidebar['languages'] = LanguageAvailable::orderBy('name', 'ASC')->has('languages')->get();

		/** Resolution **/
		$sidebar['resolutions'] =  ResolutionAvailable::orderBy('amount', 'DESC')->has('resolutions')->get();

		/** Rating **/
		$sidebar['rating']['min'] = Film::has('links')->orderBy('imdb_rating', 'ASC')->first()->imdb_rating;
		$sidebar['rating']['max'] = Film::has('links')->orderBy('imdb_rating', 'DESC')->first()->imdb_rating;
		$sidebar['rating']['from'] = Film::has('links')->orderBy('imdb_rating', 'ASC')->first()->imdb_rating;
		$sidebar['rating']['to'] = Film::has('links')->orderBy('imdb_rating', 'DESC')->first()->imdb_rating;
		$sidebar['rating']['step'] = 0.1;

		/** Year **/
		$sidebar['year']['min'] = Film::has('links')->orderBy('year', 'DESC')->first()->year;
		$sidebar['year']['max'] = Film::has('links')->orderBy('year', 'ASC')->first()->year;
		$sidebar['year']['to'] = Film::has('links')->orderBy('year', 'DESC')->first()->year;
		$sidebar['year']['from'] = Film::has('links')->orderBy('year', 'ASC')->first()->year;
		$sidebar['year']['step'] = 10;


		return $sidebar;
	}

}
