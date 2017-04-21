<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Calipsu_Social_Publish
 * @subpackage Calipsu_Social_Publish/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Calipsu_Social_Publish
 * @subpackage Calipsu_Social_Publish/includes
 * @author     Patrice LAURENT <laurent.patrice@gmail.com>
 */
class Calipsu_Social_Publish_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if ( ! function_exists ( 'curl_version' ) )
		{
			deactivate_plugins (basename (dirname (__FILE__)) . '/' . basename (__FILE__));
			die( "This plugin requires the <a href='http://www.php.net/manual/en/intro.curl.php'>PHP libcurl extension</a> be installed." );
		}
		if ( ! version_compare( PHP_VERSION, '5.4.0', '>=' ) )
		{
			deactivate_plugins (basename (dirname (__FILE__)) . '/' . basename (__FILE__));
			die( "This plugin requires the <a href='http://php.net/'>PHP 5.4</a> be installed." );
		}
		do_action( 'calipsu_sp_activate' );
	}

}
