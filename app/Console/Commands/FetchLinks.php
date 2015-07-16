<?php

namespace FilmsOnYoutube\Console\Commands;

use Illuminate\Console\Command;
// Use the DispatchJobs
use Illuminate\Foundation\Bus\DispatchesJobs;


// Use the extract library
use FilmsOnYoutube\Library\Extract;
// Use the Link model
use FilmsOnYoutube\Models\Link\Link;
// Use the Film model
use FilmsOnYoutube\Models\Film\Film;
// Use the job
use FilmsOnYoutube\Jobs\FetchFilmInformation;



class FetchLinks extends Command
{

 use DispatchesJobs;

 protected $signature = 'links:fetch';
 protected $description = 'Fetch YouTube links from Reddit API.';

 public function __construct()
 {
    parent::__construct();
}

public function handle()
{
    // Remote link to gather YouTube links in JSON format
    $remote_link = 'http://www.reddit.com/r/fullmoviesonyoutube/new/.json';

        // Fetch the content
    $json_content = file_get_contents($remote_link);

        // Decode the content
    $decoded_content = json_decode($json_content, true);

        // Loop through
    foreach($decoded_content['data']['children'] as $entry)
    {
        $reddit_id = $entry['data']['id'];

        $youtube_id = Extract::youtubeID($entry['data']['url']);
        $information = Extract::titleYear($entry['data']['title']);

        // If we can extract the Youtube ID and Title/Year
        if($youtube_id && $information)
        {
            // Check if we have already stored it (even if deleted)
            $link = Link::withTrashed()->where('youtube_id', $youtube_id)->where('reddit_id', $reddit_id)->first();

            if(!$link)
            {
                // We haven't stored it
                 // Create entry or update if exists (we use the reddit id the unqiue key)
                $link = Link::create(['youtube_id' => $youtube_id, 'reddit_id' => $reddit_id]);
                $this->dispatch(new FetchFilmInformation($link, $information['title'], $information['year'], $information['quality']));
            }

        }
    }

    return;
}
}
