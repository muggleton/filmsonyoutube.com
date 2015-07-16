<?php
namespace FilmsOnYoutube\Models\Film\Language;

use Illuminate\Database\Eloquent\Model;

class Available extends Model
{
    // Guarded attributes to allow mass assignment
	protected $guarded = ['id'];
	public $timestamps = false;
	public $appends = ['checked'];

	protected $table = 'film_languages_available';
	
	public function languages()
	{
		return $this->hasMany('FilmsOnYoutube\Models\Film\Language\Language', 'language_id', 'id');
	}

	// Custom attribute so angular checkboxes on sidebar are checked by default
	public function getCheckedAttribute()
	{
		return true;
	}

}
