<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Calipsu_Social_Publish
 * @subpackage Calipsu_Social_Publish/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'api';
// add error/update messages

// check if the user have submitted the settings
// wordpress will add the "settings-updated" $_GET parameter to the url
if ( isset( $_GET['settings-updated'] ) ) {
// add settings saved message with the class of "updated"
	add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
}

// show error/update messages
//settings_errors( 'wporg_messages' );
?>
<div class="wrap">
    <h1><img src="<?php echo $this->icon_url ?>" alt="Calipsu Social Publish" />&nbsp;<?php echo esc_html( get_admin_page_title() ); ?></h1>
    <hr />
	<?php settings_errors(); ?>
    <h2 class="nav-tab-wrapper">
        <a href="?page=calipsu-social-publish&tab=api" class="nav-tab <?php echo $active_tab == 'api' ? 'nav-tab-active' : ''; ?>"><?php _e('API Settings', 'calipsu-social-post') ?></a>
        <a href="?page=calipsu-social-publish&tab=authorize" class="nav-tab <?php echo $active_tab == 'authorize' ? 'nav-tab-active' : ''; ?>"><?php _e('Authorize', 'calipsu-social-post') ?></a>
        <a href="?page=ccalipsu-social-publish&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('General', 'calipsu-social-post') ?></a>
    </h2>

    <form method="post" action="options.php">
		<?php
		settings_fields( 'social_publish_option_group' );
		if( $active_tab == 'api' ) {
			do_settings_sections( 'calipsu-social-publish' );
		}elseif( $active_tab == 'authorize' ){
			do_settings_sections( 'calipsu-social-post-authorize' );
		}elseif( $active_tab == 'general' ){
			do_settings_sections( 'calipsu-social-post-general' );
		}
		submit_button();
		?>
    </form>
</div>