<?php

namespace FilmsOnYoutube\Models\Film\Cast;
use Illuminate\Database\Eloquent\Model;

class Cast extends Model
{
	protected $table = 'film_cast';
	protected $fillable = ['film_id', 'cast_id'];
	protected $visible = ['cast_id', 'name'];
	protected $hidden = ['film_id'];
	protected $appends = ['name'];

	public $timestamps = false;
	
	public function film()
	{
		return $this->hasOne('FilmsOnYoutube\Models\Film\Film', 'film_id', 'id');
	}

	public function cast()
	{
		return $this->hasOne('FilmsOnYoutube\Models\Film\Cast\Available', 'id', 'cast_id');
	}

	public function getNameAttribute()
	{
		return $this->cast->name;
	}
}
