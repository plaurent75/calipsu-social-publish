<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Calipsu_Social_Publish
 * @subpackage Calipsu_Social_Publish/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Calipsu_Social_Publish
 * @subpackage Calipsu_Social_Publish/admin
 * @author     Patrice LAURENT <laurent.patrice@gmail.com>
 */
class Calipsu_Social_Publish_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The settings options data.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      mixed    $social_publish_options    Value set for the option name.
	 */
	private $social_publish_options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->icon_url = plugins_url( '/images/logo_small.png' , __FILE__ );
	}
	/**
	 * List allowed Network
	 *
	 * @since    1.0.0
	 * @return    array $networks   The list of allowed Social Networks
	 */

	private function networks(){
		$networks = [
			'facebook' => [
				'field1' => __('Facebook App ID', 'calipsu-social-publish'),
				'field2' => __('Facebook App Secret', 'calipsu-social-publish'),
			],
			'twitter' => [
				'field1' => __('Twitter Consumer Key', 'calipsu-social-publish'),
				'field2' => __('Twitter Consumer Secret', 'calipsu-social-publish'),
			],
			'linkedin' => [
				'field1' => __('Linkedin ID Client', 'calipsu-social-publish'),
				'field2' => __('Linkedin Secret Client', 'calipsu-social-publish'),
			],
		];
		return $networks;
	}

	/**
	 * Add a settings link to the Plugins page
	 */
	function add_settings_link( $links, $file ){
		$this_plugin = $this->plugin_name.'/'.$this->plugin_name.'.php';

		if ( $file == $this_plugin ){
			$settings_link = '<a href="options-general.php?page=calipsu-social-publish">'.__("Settings", "calipsu-social-publish").'</a>';
			array_unshift( $links, $settings_link );
		}
		return $links;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/calipsu-social-publish-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/calipsu-social-publish-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Top level menu
	 *
	 * @since    1.0.0
	 */
	function options_page() {

		add_menu_page(
			__('Social Publish', 'calipsu-social-publish'),
			__('Social Publish', 'calipsu-social-publish'),
			'manage_options',
			'calipsu-social-publish',
			[$this,'options_page_html'],
			$this->icon_url
		);
	}
	/**
	 * top level menu: callback functions
	 *
	 * @since    1.0.0
	 */
	public function options_page_html() {
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$this->social_publish_options = get_option( 'social_publish_option_name' );
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/calipsu-social-publish-admin-display.php';
	}

	/**
	 * Register Settings
	 *
	 * @since    1.0.0
	 */
	public function settings_init(){

		register_setting(
			'social_publish_option_group', // option_group
			'social_publish_option_name', // option_name
			[ $this, 'social_publish_sanitize' ] // sanitize_callback
		);
		add_settings_section(
			'social_publish_setting_section', // id
			null, // title
			null, // callback
			'calipsu-social-publish' // page
		);

		foreach ($this->networks() as $network => $data) {
			$title1 = $data['field1'];
			$id1 = $network."_app";
			add_settings_field(
				$id1,
				$title1,
				[ $this, 'create_setting_field' ],
				"calipsu-social-publish",
				"social_publish_setting_section",
				[
					'id' => $id1,
					'type' => 'text',
				]
			);
			if(!empty($data['field2'])) :
				$title2 = $data['field2'];
				$id2 = $network."_secret";

				add_settings_field(
					$network."_secret",
					$title2,
					[$this,'create_setting_field'],
					"calipsu-social-publish",
					"social_publish_setting_section",
					[
						'id' => $id2,
						'type' => 'password',
					]
				);
			endif;

		}
	}

	/**
	 * Display element
	 * Callback Function for add_settings_field
	 *
	 * @param array     $args   An array of arguments
	 * @since    1.0.0
	 */
	public function create_setting_field($args){
		$id     = $args['id'];
		$type     = $args['type'];
		printf(
			'<input class="regular-text" type="'.$type.'" name="social_publish_option_name['.$id.']" id="'.$id.'" value="%s">',
			isset( $this->social_publish_options[$id] ) ? esc_attr( $this->social_publish_options[$id]) : ''
		);
	}

	/**
	 * Sanitize Callback Function
	 *
	 * @param array $input
	 *
	 * @return array $sanitary_values
	 *
	 * @since    1.0.0
	 */
	public function social_publish_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['facebook_app'] ) ) {
			$sanitary_values['facebook_app'] = sanitize_text_field( $input['facebook_app'] );
		}

		if ( isset( $input['facebook_secret'] ) ) {
			$sanitary_values['facebook_secret'] = sanitize_text_field( $input['facebook_secret'] );
		}

		return $sanitary_values;
	}

}
