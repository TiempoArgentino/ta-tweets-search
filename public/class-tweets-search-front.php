<?php

class Tweets_Search_Front
{
    public function __construct()
    {
        
    }

    public function get_tweets($post_id)
    {
        $tweet = tweets_options()->get_data_row($post_id,'post_id','tweets');
        if($tweet != null) {
            return $tweet->{'tweets'};
        }   
    }
    

}


function tweets_search_front()
{
    return new Tweets_Search_Front();
}

tweets_search_front();