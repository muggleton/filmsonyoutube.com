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
       $link = Link::orderBy('last_checked', 'ASC')->first();

        if($link)
        {
         try 
         {
             $remote_content = file_get_contents('https://www.youtube.com/watch?v=' . $link->youtube_id);
             if(strstr($remote_content, 'Sorry about that.')) 
             {
                $link->resolution()->delete();
                $link->delete();
                return;
            } 
            $link->last_checked = date('Y-m-d H:i:s');

        } 
        catch(Exception $e) 
        {
            $link->last_checked = date('Y-m-d H:i:s');

        }

        $link->save();
    }
    return;
}

}
