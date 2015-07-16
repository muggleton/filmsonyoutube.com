<?php
namespace FilmsOnYoutube\Models\Film\Cast;

use Illuminate\Database\Eloquent\Model;

class Available extends Model
{
    // Guarded attributes to allow mass assignment
	protected $guarded = ['id'];
	public $timestamps = false;

	protected $table = 'film_cast_available';
	
	public function casts()
	{
		return $this->hasMany('FilmsOnYoutube\Models\Film\Cast\Cast', 'cast_id', 'id');
	}
}
