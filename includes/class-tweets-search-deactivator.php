<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://genosha.com.ar
 * @since      1.0.0
 *
 * @package    Tweets_Search
 * @subpackage Tweets_Search/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Tweets_Search
 * @subpackage Tweets_Search/includes
 * @author     Genosha <juan.e@genosha.com.ar>
 */
class Tweets_Search_Deactivator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate()
	{

		self::delete_tables();
	}

	public static function delete_tables()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'tweets';
		$sql = 'DROP TABLE IF EXISTS ' . $table_name;
		$wpdb->query($sql);
	}
}
