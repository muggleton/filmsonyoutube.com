<?php

namespace FilmsOnYoutube\Models\Film;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    // Guarded attributes to allow mass assignment
	protected $guarded = ['id'];

	protected $hidden = ['updated_at', 'created_at', 'id', 'poster'];

	protected $appends = ['posterlink'];

	protected $table = 'films';

	public function links()
	{
		return $this->hasMany('FilmsOnYoutube\Models\Link\Link', 'film_id', 'id');
	}

	public function genres()
	{
		return $this->hasMany('FilmsOnYoutube\Models\Film\Genre\Genre', 'film_id', 'id');
	}

	public function directors()
	{
		return $this->hasMany('FilmsOnYoutube\Models\Film\Director\Director', 'film_id', 'id');
	}

	public function languages()
	{
		return $this->hasMany('FilmsOnYoutube\Models\Film\Language\Language', 'film_id', 'id');
	}

	public function cast()
	{
		return $this->hasMany('FilmsOnYoutube\Models\Film\Cast\Cast', 'film_id', 'id');
	}
	// Check whether there is a poster
	public function getPosterLinkAttribute()
	{
		$poster = $this->poster;

		if($poster !== '')
		{
			return '/poster/' . $this->id;
		}
		else
		{
			return '/assets/img/posters/missing.jpg';
		}
	}
}
