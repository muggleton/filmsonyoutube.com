<?php

namespace FilmsOnYoutube\Console\Commands;

use Illuminate\Console\Command;
use FilmsOnYoutube\Models\Link\Link;

class CheckLink extends Command
{
    protected $signature = 'links:check';
    protected $description = 'Command description.';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Find a link to check
//        $link = Link::orderBy('last_checked', 'ASC')->first();
        $link = Link::find(2);
        // Have we found one?
        if($link)
        {
            // Fetch remote contents
            if($remote_content == file_get_contents('https://www.youtube.com/watch?v=' . $link->youtube_id))
            {
                // Check if remote contents contains the unavailable string
                if(strstr($remote_content, 'Sorry about that.'))
                {
                    // It's been removed
                    // Delete resolution
                    $link->resolution()->delete();

                    // Delete link
                    $link->delete();

                    // Return 
                    return;
                }

                // Link hasn't been blocked
                // update last checked
                $link->last_checked = date('Y-m-d H:i:s');

                // Save
                $link->save();

                // Return
                return;
            }
        }

        return;
    }
}
