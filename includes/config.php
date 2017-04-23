<?php

/**
 * HybridAuth
 * http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
 * (c) 2009-2015, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
 */
// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------
$settings = get_option( 'social_publish_option_name' );

/*Facebook*/
$fb_app = apply_filters('calipsu_facebook_app',isset($settings['facebook_app']) ? $settings['facebook_app'] : '');
$fb_secret = apply_filters('calipsu_facebook_secret',isset($settings['facebook_secret']) ? $settings['facebook_secret'] : '');
$fb_enabled = !empty($fb_app) && !empty($fb_secret) ? true : false;

/*Twitter*/
$tw_app = apply_filters('calipsu_twitter_app',isset($settings['twitter_app']) ? $settings['twitter_app'] : '');
$tw_secret = apply_filters('calipsu_twitter_secret',isset($settings['twitter_secret']) ? $settings['twitter_secret'] : '');
$tw_enabled = !empty($fb_app) && !empty($fb_secret) ? true : false;

/*Linkedin*/
$lki_app = apply_filters('calipsu_linkedin_app',isset($settings['linkedin_app']) ? $settings['linkedin_app'] : '');
$lki_secret = apply_filters('calipsu_linkedin_secret',isset($settings['linkedin_secret']) ? $settings['linkedin_secret'] : '');
$lki_enabled = !empty($lki_app) && !empty($lki_secret) ? true : false;
return
		array(
			"base_url" => plugins_url( '/3rd-party/hybridauth/hybridauth/', __FILE__),
			//"base_url" => plugins_url( '/../admin/partials/connect.php/', __FILE__),
			"providers" => array(
				"Facebook" => array(
					"enabled" => $fb_enabled,
					"keys" => array(
						"id" => $fb_app,
						"secret" => $fb_secret,
					),
					"scope"   => 'public_profile,publish_actions,manage_pages,publish_pages', // optional
					"display" => "popup", // optional
					"pagechoice" => true,
				),
				"Twitter" => array(
					"enabled" => $tw_enabled,
					"keys" => array(
						"key" => $tw_app,
						"secret" => $tw_secret,
					),
					"pagechoice" => false,

				),
				"LinkedIn" => array(
					"enabled" => $lki_enabled,
					"keys" => array(
						"key" => $lki_app,
						"secret" => $lki_secret,
					),
					"pagechoice" => true,
					"scope" => "w_share,r_fullprofile,rw_company_admin,r_basicprofile,r_emailaddress"

				),

			),
			// If you want to enable logging, set 'debug_mode' to true.
			// You can also set it to
			// - "error" To log only error messages. Useful in production
			// - "info" To log info and error messages (ignore debug messages)
			"debug_mode" => apply_filters('calipsu_debug_mod',false),
			// Path to file writable by the web server. Required if 'debug_mode' is not false
			"debug_file" => apply_filters('calipsu_debug_file',''),
);
