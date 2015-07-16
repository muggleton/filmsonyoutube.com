<?php

namespace FilmsOnYoutube\Models\Film\Director;
use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
	protected $table = 'film_directors';
	protected $fillable = ['film_id', 'director_id'];
	protected $visible = ['director_id', 'name'];
	protected $hidden = ['film_id'];
	protected $appends = ['name'];

	public $timestamps = false;


	
	public function film()
	{
		return $this->hasOne('FilmsOnYoutube\Models\Film\Film', 'film_id', 'id');
	}

	public function director()
	{
		return $this->hasOne('FilmsOnYoutube\Models\Film\Director\Available', 'id', 'director_id');
	}

	public function getNameAttribute()
	{
		return $this->director->name;
	}
}
