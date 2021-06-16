<?php

/**
 * Fired during plugin activation
 *
 * @link       https://genosha.com.ar
 * @since      1.0.0
 *
 * @package    Tweets_Search
 * @subpackage Tweets_Search/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Tweets_Search
 * @subpackage Tweets_Search/includes
 * @author     Genosha <juan.e@genosha.com.ar>
 */
class Tweets_Search_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		self::create_table_tweets();
	}

	public static function create_tables($table, $sql)
	{
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$table = $wpdb->prefix . $table;

		$sql = 'CREATE TABLE IF NOT EXISTS ' . $table . $sql . $charset_collate;

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	public static function create_table_tweets()
	{
		$ads_table = 'tweets';

		$sql =  ' ( `id` INT NOT NULL AUTO_INCREMENT , `post_id` VARCHAR(100) NOT NULL , `tweets` MEDIUMTEXT NOT NULL, PRIMARY KEY (`id`))';

		self::create_tables($ads_table, $sql);
	}
}
