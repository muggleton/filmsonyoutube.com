<?php

namespace FilmsOnYoutube\Models\Link;

use Illuminate\Database\Eloquent\Model;


class Link extends Model
{
	// Guarded attributes to allow mass assignment
	protected $guarded = ['id'];

	// Register custom attributes
	protected $appends = ['embed', 'url'];

	// Hidden
	protected $hidden = ['id', 'updated_at', 'deleted_at', 'film_id', 'reddit_id', 'youtube_id', 'last_checked'];

	public function film()
	{
		return $this->hasOne('FilmsOnYoutube\Models\Film\Film', 'id', 'film_id');
	}

	public function resolution()
	{
		return $this->hasOne('FilmsOnYoutube\Models\Link\Resolution\Resolution', 'link_id', 'id');
	}
	
	public function getEmbedAttribute()
	{
		return '//www.youtube.com/embed/' . $this->youtube_id;
	}

	public function getUrlAttribute()
	{
		if(isset($this->film->title))
		{
			return '/' . $this->id . '/' . str_slug($this->film->title . ' ' . $this->film->year);
		}
		
	}
}
