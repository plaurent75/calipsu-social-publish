<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Calipsu_Social_Publish
 * @subpackage Calipsu_Social_Publish/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Calipsu_Social_Publish
 * @subpackage Calipsu_Social_Publish/includes
 * @author     Patrice LAURENT <laurent.patrice@gmail.com>
 */
class Calipsu_Social_Publish {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Calipsu_Social_Publish_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The hybrid config data.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $hybrid_conf    Hybrid config file path.
	 */
	protected $hybrid_conf;

	/**
	 * The settings options data.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      mixed    $social_publish_options    Value set for the option name.
	 */
	protected $social_publish_options;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'calipsu-social-publish';
		$this->version = '1.0.0';
		$this->hybrid_conf = apply_filters('calipsu_hybrid_conf',dirname( dirname( __FILE__ ) ) . '/includes/config.php');
		$this->social_publish_options = get_option( 'social_publish_option_name' );

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Calipsu_Social_Publish_Loader. Orchestrates the hooks of the plugin.
	 * - Calipsu_Social_Publish_i18n. Defines internationalization functionality.
	 * - Calipsu_Social_Publish_Admin. Defines all hooks for the admin area.
	 * - Calipsu_Social_Publish_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once dirname( dirname( __FILE__ ) ) . '/includes/class-calipsu-social-publish-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once dirname( dirname( __FILE__ ) ) . '/includes/class-calipsu-social-publish-i18n.php';

		/**
		 * The class HybridAuth
		 */
		require_once dirname( dirname( __FILE__ ) ) . '/includes/3rd-party/hybridauth/hybridauth/Hybrid/Auth.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once dirname( dirname( __FILE__ ) ) . '/admin/class-calipsu-social-publish-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once dirname( dirname( __FILE__ ) ) . '/public/class-calipsu-social-publish-public.php';

		$this->loader = new Calipsu_Social_Publish_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Calipsu_Social_Publish_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Calipsu_Social_Publish_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Calipsu_Social_Publish_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_hybrid_conf(), $this->get_social_publish_options() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_filter( 'plugin_action_links', $plugin_admin, 'add_settings_link', 10, 2 );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'settings_init' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'options_page' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Calipsu_Social_Publish_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Calipsu_Social_Publish_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * The conf for hybrid auth.
	 *
	 * @since     1.0.0
	 * @return    string    Config.php path.
	 */
	public function get_hybrid_conf() {
		return $this->hybrid_conf;
	}

	/**
	 * The options settings data.
	 *
	 * @since     1.0.0
	 * @return    mixed     Value set for the option name
	 */
	public function get_social_publish_options() {
		return $this->social_publish_options;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
