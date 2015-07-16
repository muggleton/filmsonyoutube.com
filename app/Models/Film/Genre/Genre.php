<?php

namespace FilmsOnYoutube\Models\Film\Genre;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
	protected $table = 'film_genres';
	protected $fillable = ['film_id', 'genre_id'];
	protected $visible = ['genre_id', 'name'];
	protected $hidden = ['film_id'];
	protected $appends = ['name'];

	public $timestamps = false;


	
	public function film()
	{
		return $this->hasOne('FilmsOnYoutube\Models\Film\Film', 'film_id', 'id');
	}

	public function genre()
	{
		return $this->hasOne('FilmsOnYoutube\Models\Film\Genre\Available', 'id', 'genre_id');
	}

	public function getNameAttribute()
	{
		return $this->genre->name;
	}
}
