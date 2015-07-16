<?php
// Extract library
// used to extract information from API responses such as
// a movies title or year.

namespace FilmsOnYoutube\Library;

class Extract
{
	public static function youtubeID($url)
	{
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match))
		{
			return $match[1];
		}
		return false;
	}

	public static function number($string)
	{
		return filter_var($string, FILTER_SANITIZE_NUMBER_INT);
	}
	
	public static function titleYear($title)
	{
		$txt = $title;
		$title_regex = '((?:[A-Za-z0-9 _.,!:"\']*))';
		$anything_regex = '.*?';
		$year_regex = '(([0-9]{4}))';
		$quality_regex = '(([0-9]{3,4}p))';

		// Match title and year
		if (preg_match_all("/". $title_regex . $anything_regex . $year_regex .  $anything_regex . $quality_regex . "/is", $txt, $matches))
		{
			// Check they are not empty
			$title = $matches[1][0];
			$year = $matches[2][0];

			// Remove p from quality
			$quality = str_replace('p', '', $matches[4][0]);

			if($title !== '' && $year !== '')
			{
				return ['title' => $title, 'year' => $year, 'quality' => $quality];
			}

			return false;
			
		}
		// Otherwise return false
		return false;
	}
}