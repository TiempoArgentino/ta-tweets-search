<?php


class Tweets_Search_Curl
{
    public function search($hash)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.twitter.com/2/tweets/search/recent?query=' . $hash . '&max_results=10',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . get_option('twitter_token')
            ),
        ));

        $response = curl_exec($curl);

        $data = json_decode($response)->{'data'};

        curl_close($curl);

        return $data;
    }

    public function tweet($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.twitter.com/2/tweets/' . $id . '?tweet.fields=author_id,entities,created_at',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . get_option('twitter_token')
            ),
        ));

        $response = curl_exec($curl);

        $data = json_decode($response);

        curl_close($curl);

        return  $data;
    }

    public function user($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.twitter.com/2/users/' . $id . '?user.fields=name,profile_image_url,username',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . get_option('twitter_token')
            ),
        ));

        $response = curl_exec($curl);

        $data = json_decode($response)->{'data'};

        curl_close($curl);

        return  $data;
    }

    public function save_tweets($hash)
    {
        $tweets = '<div id="tweets">';
        $tweets .= '<p id="hash"><a href="https://twitter.com/hashtag/' . urlencode($hash) . '?src=hashtag_click" target="_blank">#' . $hash . ' <span><img src="' . esc_url( plugins_url(). '/tweets-search/public/img/twitter-brands.png') . '" ></span></a></p>';
        foreach ($this->search($hash) as $d) {
            $tweet = $d->{'id'};
            foreach ($this->tweet($tweet) as $t) {
                $user = $this->user($t->{'author_id'});

                $tweets .= '<div class="tweet">';
                $tweets .= '<div class="tweet-user">
                    <div class="profile"><img src="' . $user->{'profile_image_url'} . '" /> 
                    <div class="user-name">
                    <a href="https://twitter.com/' . $user->{'username'} . '" target="_blank" class="user-name-link">' . $user->{'name'} . '</a>
                    <a href="https://twitter.com/' . $user->{'username'} . '" target="_blank" class="username-link">@' . $user->{'username'} . '</a>
                    </div>
                    </div>
                    
                    </div>';
                $tweets .= '<p class="text-twit">' . $t->{'text'} . '</p>';
                //Hash
                if (isset($t->{'entities'}->{'hashtags'})) {
                    $tweets .= '<div class="tweet-hash">';
                    foreach ($t->{'entities'}->{'hashtags'} as $h) {
                        $tweets .= '<span class="tag">#' . $h->{'tag'} . '</span>';
                    }
                    $tweets .= '</div>';
                }
                $tweets .= '<div class="tweet-date">' . date("d-m-Y", strtotime($t->{'created_at'})) . '</div>';
                $tweets .= '</div>';
            }
        }
        $tweets .= '</div>';
        return maybe_serialize($tweets);
    }
}

function tweets_curl()
{
    return new Tweets_Search_Curl();
}

tweets_curl();
