<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.patricelaurent.net
 * @since             1.0.0
 * @package           Calipsu_Social_Publish
 *
 * @wordpress-plugin
 * Plugin Name:       Calipsu Social Publish
 * Plugin URI:        https://www.patricelaurent.net
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Patrice LAURENT
 * Author URI:        https://www.patricelaurent.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       calipsu-social-publish
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-calipsu-social-publish-activator.php
 */
function activate_calipsu_social_publish() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-calipsu-social-publish-activator.php';
	Calipsu_Social_Publish_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-calipsu-social-publish-deactivator.php
 */
function deactivate_calipsu_social_publish() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-calipsu-social-publish-deactivator.php';
	Calipsu_Social_Publish_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_calipsu_social_publish' );
register_deactivation_hook( __FILE__, 'deactivate_calipsu_social_publish' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-calipsu-social-publish.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_calipsu_social_publish() {

	$plugin = new Calipsu_Social_Publish();
	$plugin->run();

}
run_calipsu_social_publish();
