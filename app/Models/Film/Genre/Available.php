<?php
namespace FilmsOnYoutube\Models\Film\Genre;

use Illuminate\Database\Eloquent\Model;

class Available extends Model
{
    // Guarded attributes to allow mass assignment
	protected $guarded = ['id'];
	public $timestamps = false;
	public $appends = ['checked'];

	protected $table = 'film_genres_available';
	
	public function genres()
	{
		return $this->hasMany('FilmsOnYoutube\Models\Film\Genre\Genre', 'genre_id', 'id');
	}

	// Custom attribute so angular checkboxes on sidebar are checked by default
	public function getCheckedAttribute()
	{
		return true;
	}

}
