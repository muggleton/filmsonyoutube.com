<?php

namespace FilmsOnYoutube\Jobs;

use FilmsOnYoutube\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

// Extract library
use FilmsOnYoutube\Library\Extract;
// Use Film model
use FilmsOnYoutube\Models\Film\Film;
// Use the Film Genre model
use FilmsOnYoutube\Models\Film\Genre\Genre;
// Use the film genre available model
use FilmsOnYoutube\Models\Film\Genre\Available as GenreAvailable;
// Use the Film Language model
use FilmsOnYoutube\Models\Film\Language\Language;
// Use the film language available model
use FilmsOnYoutube\Models\Film\Language\Available as LanguageAvailable;
// Use the Film director model
use FilmsOnYoutube\Models\Film\Director\Director;
// Use the film director available model
use FilmsOnYoutube\Models\Film\Director\Available as DirectorAvailable;
// Use the Film cast model
use FilmsOnYoutube\Models\Film\Cast\Cast;
// Use the film cast available model
use FilmsOnYoutube\Models\Film\Cast\Available as CastAvailable;
// Use Link Model
use FilmsOnYoutube\Models\Link\Link;
// Use Link resolution
use FilmsOnYoutube\Models\Link\Resolution\Resolution;
// Use the film genre available model
use FilmsOnYoutube\Models\Link\Resolution\Available as ResolutionAvailable;

class FetchFilmInformation extends Job implements SelfHandling, ShouldQueue
{
    protected $link, $title, $year, $resolution;

    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Link $link, $title, $year, $resolution)
    {
        // Assign the link to the $link variable
        $this->link = $link;
        $this->title = $title;
        $this->year = $year;
        $this->resolution = $resolution;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // See if we already have the film
        $film = Film::where('title', $this->title)->where('year', $this->year)->first();


        if($film)
        {
            // We already have the film in our database
            // Update the link with the film i.d
            $this->link->film_id = $film->id;
            $this->link->save();

           // Release the job
            return $this->release();
        }

        // Otherwise we need to make a request
        $response = json_decode(file_get_contents('http://www.omdbapi.com/?t=' . urlencode($this->title) . '&y=' . urlencode($this->year) . '&plot=long&r=json'), true);
        
        // Check the response is successful
        if($response['Response'] == "False")
        {
            // Movie not found delete the link!
            $this->link->delete();

            // Release the job
            return $this->release();
        }

        // Check if poster exists, if it does, save it 
        if($response['Poster'] !== 'N/A')
        {
            try
            {

                $poster = file_get_contents($response['Poster']);
                $location = env('POSTER_PATH') . '/' . $this->link->id . '.jpg';
                file_put_contents($location, $poster);
            }
            catch(Exception $e)
            {
                $location = '';
            }
        }
        else
        {
           $location = '';
       }

        // Otherwise add the new film to our database
       $film = Film::updateOrCreate(['imdb_id' => $response['imdbID']], [
        'title'             => $response['Title'],
        'year'              => $response['Year'],
        'plot'              => $response['Plot'],
        'poster'            => $location,
        'imdb_rating'       => $response['imdbRating'],
        'imdb_votes'        => $response['imdbVotes'],
        'imdb_id'           => $response['imdbID'],
        'runtime_minutes'   => Extract::number($response['Runtime'])
        ]);

       $this->link->film_id = $film->id;
       $this->link->save();



        // Genres
       $genres = explode(',', $response['Genre']);
       foreach($genres as $genre)
       {
        $genre = GenreAvailable::updateOrCreate(['name' => trim(rtrim($genre))]);
        Genre::updateOrCreate(['film_id' => $film->id, 'genre_id' => $genre->id]);
    }

        // Language
    $languages = explode(',', $response['Language']);
    foreach($languages as $language)
    {
        $language = LanguageAvailable::updateOrCreate(['name' => trim(rtrim($language))]);
        Language::updateOrCreate(['film_id' => $film->id, 'language_id' => $language->id]);
    }

        // Directors
    $directors = explode(',', $response['Director']);
    foreach($directors as $director)
    {
        $director = DirectorAvailable::updateOrCreate(['name' => trim(rtrim($director))]);
        Director::updateOrCreate(['film_id' => $film->id, 'director_id' => $director->id]);
    }

        // Cast
    $casts = explode(',', $response['Actors']);
    foreach($casts as $cast)
    {
        $cast = CastAvailable::updateOrCreate(['name' => trim(rtrim($cast))]);
        Cast::updateOrCreate(['film_id' => $film->id, 'cast_id' => $cast->id]);
    }

        // Resolution
    $resolution = ResolutionAvailable::updateOrCreate(['amount' => $this->resolution]);
    Resolution::updateOrCreate(['link_id' => $this->link->id, 'resolution_id' => $resolution->id]);
    

    return $this->release();
    
}
}
