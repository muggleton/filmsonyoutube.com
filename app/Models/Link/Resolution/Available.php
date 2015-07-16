<?php
namespace FilmsOnYoutube\Models\Link\Resolution;

use Illuminate\Database\Eloquent\Model;

class Available extends Model
{
    // Guarded attributes to allow mass assignment
	protected $guarded = ['id'];
	public $timestamps = false;
	public $appends = ['checked'];

	protected $table = 'link_resolution_available';
	
	public function resolutions()
	{
		return $this->hasMany('FilmsOnYoutube\Models\Link\Resolution\Resolution', 'resolution_id', 'id');
	}

	// Custom attribute so angular checkboxes on sidebar are checked by default
	public function getCheckedAttribute()
	{
		return true;
	}

}
