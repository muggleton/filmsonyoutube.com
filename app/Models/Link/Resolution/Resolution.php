<?php

namespace FilmsOnYoutube\Models\Link\Resolution;
use Illuminate\Database\Eloquent\Model;

class Resolution extends Model
{
	protected $table = 'link_resolution';
	protected $fillable = ['link_id', 'resolution_id'];
	protected $visible = ['amount'];
	protected $hidden = ['link_id'];
	protected $appends = ['amount'];

	public $timestamps = false;


	
	public function link()
	{
		return $this->hasOne('FilmsOnYoutube\Models\Link\Link', 'link_id', 'id');
	}

	public function resolution()
	{
		return $this->hasOne('FilmsOnYoutube\Models\Link\Resolution\Available', 'id', 'resolution_id');
	}

	public function getAmountAttribute()
	{
		return $this->resolution->amount;
	}
}
