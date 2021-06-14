<?php

class Tweets_Search_Options
{

    public function __construct()
    {
        add_action('admin_menu', [$this, 'tweets_options']);

        $this->token_save();

        add_action('add_meta_boxes', [$this, 'post_meta']);
        add_action('save_post', [$this, 'save']);
    }

    public function insert_data($table, $data, $replace)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $table;
        $results = $wpdb->insert($table_name, $data, $replace);
        return $results;
    }

    public function get_data_row($post_id, $where, $table)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $table;
        $results = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM " . $table_name . " WHERE " . $where . "=%d", $post_id)
        );
        return $results;
    }

    public function update_data($table, $data, $where, $data_format, $where_format)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $table;
        $result = $wpdb->update($table_name, $data, $where, $data_format, $where_format);
        return $result;
    }

    public function delete_data($table, $where, $where_format)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $table;
        $result = $wpdb->delete($table_name, $where, $where_format);
        return $result;
    }

    public function tweets_options()
    {
        add_submenu_page(
            'options-general.php',
            __('Tweets', 'tweets-search'),
            __('Tweets', 'tweets-search'),
            'manage_options',
            'tweets-options',
            [$this, 'tweets_callback']
        );
    }

    public function tweets_callback()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/tweets-search-admin-display.php';
    }

    public function token_save()
    {
        if(isset($_POST['twitter_token'])){
            update_option('twitter_token',$_POST['twitter_token'],true);
        }
    }

    /**
     * metabox
     */

    public function post_meta($post_type)
    {
        $post_types = ['ta_article'];

        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'article_hashtag',
                __('Hashtag', 'tweets-search'),
                [$this, 'render_meta_box_content'],
                $post_type,
                'side',
                'high'
            );
        }
    }

    public function render_meta_box_content($post)
    {
        wp_nonce_field('tweets_search_custom_box', 'tweets_search_custom_box_nonce');

        $value = get_post_meta($post->ID, '_post_hashtag', true);
        echo '<label for="tweets_search_field">
            ' . __('Hashtag a mostrar', 'tweets-search') . '
        </label>
        <input type="text" id="tweets_search_field" name="tweets_search_field" value="' . esc_attr($value) . '" />';
    }

    public function save($post_id)
    {
        
        if (!isset($_POST['tweets_search_custom_box_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['tweets_search_custom_box_nonce'];

        if (!wp_verify_nonce($nonce, 'tweets_search_custom_box')) {
            return $post_id;
        }

        $hashtag = sanitize_text_field($_POST['tweets_search_field']);
        $tweet = $this->get_data_row($post_id,'post_id','tweets');

        if(isset($_POST['tweets_search_field']) && !empty($_POST['tweets_search_field'])) {
            update_post_meta($post_id, '_post_hashtag', $hashtag);

            if($tweet == null) {
                $data = [
                    'post_id' => $post_id,
                    'tweets' =>  tweets_curl()->save_tweets($hashtag)
                ];
                $new = $this->insert_data('tweets', $data, ['%s', '%s']);
            } else {
                $data = [
                    'tweets' => tweets_curl()->save_tweets($hashtag)
                ];
                $where = ['id' => $tweet->id];
    
                $update = $this->update_data('tweets', $data, $where, null, null);
            }
        } else {
            if($tweet != null) {
                $where = ['id' => $tweet->id];
                $delete = $this->delete_data('tweets', $where, null);
            }
        }
    }
}

function tweets_options()
{
    return new Tweets_Search_Options();
}

tweets_options();
