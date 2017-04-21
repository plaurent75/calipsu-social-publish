<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Calipsu_Social_Publish
 * @subpackage Calipsu_Social_Publish/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Calipsu_Social_Publish
 * @subpackage Calipsu_Social_Publish/includes
 * @author     Patrice LAURENT <laurent.patrice@gmail.com>
 */
class Calipsu_Social_Publish_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'calipsu-social-publish',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
