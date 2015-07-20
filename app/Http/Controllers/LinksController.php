<?php

namespace FilmsOnYoutube\Http\Controllers;

use Illuminate\Http\Request;

use FilmsOnYoutube\Http\Requests;
use FilmsOnYoutube\Http\Controllers\Controller;
use FilmsOnYoutube\Models\Link\Link;

class LinksController extends Controller
{

    public function index(Request $request)
    {
        $links = Link::with('resolution', 'film.genres', 'film.languages', 'film.directors', 'film.cast')
        ->orderBy('links.created_at', 'DESC');

        $search         = $request->get('search');
        $genres         = $request->get('genres');
        $resolution     = $request->get('resolution');
        $rating         = $request->get('rating');
        $year           = $request->get('year');
        $languages      = $request->get('languages');

        if($search)
        {
            $links->whereHas('film', function($q) use ($search){
                $q->where('title', 'LIKE', '%' . $search . '%');
            });
        }

        if($genres)
        {
            // Remove the first comma from the list
            $genres = substr($genres, 1);
            $links->whereHas('film.genres', function($q) use ($genres)
            {
                $q->whereIn('genre_id', explode(',', $genres));
            });
        }

        if($languages)
        {
            // Remove the first comma from the list
            $languages = substr($languages, 1);
            $links->whereHas('film.languages', function($q) use ($languages)
            {
                $q->whereIn('language_id', explode(',', $languages));
            });
        }
        if($resolution)
        {
            // Take leading comma off
            $resolutions = substr($resolution, 1);

            $links->whereHas('resolution', function($q) use ($resolutions)
            {
                $q->whereIn('resolution_id', explode(',', $resolutions));
            });
        }

        if($rating)
        {
            // Explode rating for to/from
            $rating = explode(',', $rating);
            $links->whereHas('film', function($q) use ($rating){
                // From rating is set
                if(isset($rating[0]))
                {
                    $q->where('imdb_rating', '>=', $rating[0]);
                }
                // To rating is set
                if(isset($rating[1]))
                {
                  $q->where('imdb_rating', '<=', $rating[1]);
              }
          });

        }

        if($year)
        {
            $year = explode(',', $year);
            $links->whereHas('film', function($q) use ($year){
                // From rating is set
                if(isset($year[0]))
                {
                    $q->where('year', '>=', $year[0]);
                }
                // To rating is set
                if(isset($year[1]))
                {
                  $q->where('year', '<=', $year[1]);
              }
          });

        }

        return $links->paginate(9);
    }

    public function show($id)
    {
        // Find link
        $link = Link::whereId($id)->with('resolution', 'film.genres', 'film.languages', 'film.directors', 'film.cast')->first();
    }

}
