<?php

namespace FilmsOnYoutube\Models\Film\Language;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
	protected $table = 'film_languages';
	protected $fillable = ['film_id', 'language_id'];
	protected $visible = ['language_id', 'name'];
	protected $hidden = ['film_id'];
	protected $appends = ['name'];

	public $timestamps = false;


	
	public function film()
	{
		return $this->hasOne('FilmsOnYoutube\Models\Film\Film', 'film_id', 'id');
	}

	public function language()
	{
		return $this->hasOne('FilmsOnYoutube\Models\Film\Language\Available', 'id', 'language_id');
	}

	public function getNameAttribute()
	{
		return $this->language->name;
	}
}
