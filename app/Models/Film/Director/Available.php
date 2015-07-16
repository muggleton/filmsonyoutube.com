<?php
namespace FilmsOnYoutube\Models\Film\Director;

use Illuminate\Database\Eloquent\Model;

class Available extends Model
{
    // Guarded attributes to allow mass assignment
	protected $guarded = ['id'];
	public $timestamps = false;

	protected $table = 'film_directors_available';
	
	public function directors()
	{
		return $this->hasMany('FilmsOnYoutube\Models\Film\Director\Director', 'director_id', 'id');
	}
}
