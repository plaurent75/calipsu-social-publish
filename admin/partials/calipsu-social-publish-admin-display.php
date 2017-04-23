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
add_thickbox();

$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'api';
$action = isset( $_GET[ 'calipsu_action' ] ) ? $_GET[ 'calipsu_action' ] : 'default';
?>
<div class="wrap">
    <h1><img src="<?php echo $this->icon_url ?>" alt="Calipsu Social Publish" />&nbsp;<?php echo esc_html( get_admin_page_title() ); ?></h1>
    <hr />
	<?php settings_errors('calipsu_setting_messages'); ?>
    <h2 class="nav-tab-wrapper">
        <a href="?page=calipsu-social-publish&tab=api" class="nav-tab <?php echo $active_tab == 'api' ? 'nav-tab-active' : ''; ?>"><?php _e('API Settings', 'calipsu-social-post') ?></a>
        <a href="?page=calipsu-social-publish&tab=authorize" class="nav-tab <?php echo $active_tab == 'authorize' ? 'nav-tab-active' : ''; ?>"><?php _e('Authorize', 'calipsu-social-post') ?></a>
        <a href="?page=calipsu-social-publish&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('General', 'calipsu-social-post') ?></a>
    </h2>


		<?php
		if( $active_tab == 'api' ) {
		    ?><form method="post" action="options.php"><?php
			settings_fields( 'social_publish_option_group' );
			do_settings_sections( 'calipsu-social-publish' );
			submit_button();
        ?> </form><?php
        }elseif( $active_tab == 'authorize' ){
		    switch ($action){
                case 'authorize' :
		settings_errors('calipsu_authorize_messages');

        $get_prov = isset( $_GET[ 'provider' ] ) ? $_GET[ 'provider' ] : null;
	                if(!is_null($get_prov)) {
		                $provider = @ trim( strip_tags( $get_prov ) );
		                try {
			                $ha = new Hybrid_Auth( $this->hybrid_conf );
			                $f = $ha->authenticate($provider);
		                }
		                catch( Exception $e ) {
			                $message = "Some strange error occured, Please try again Later...";
			                switch ( $e->getCode() ) {
				                case 0 :
					                $message = "Some strange error occured.";
					                break;
				                case 1 :
					                $message = "It seems Hybridauth is not configuration properly.";
					                break;
				                case 2 :
					                $message = "It seems some details are missing in provider configuration.";
					                break;
				                case 3 :
					                $message = "It seems login provider is Unknown or Disabled.";
					                break;
				                case 4 :
					                $message = "It seems you forgot to mention provider application credentials.";
					                break;
				                case 5 :
					                $message = "Authentication has failed. Either the user has canceled the authentication or the provider refused the connection.";
					                break;
				                case 701 :
					                $message = "Authentication has failed. Either the user has canceled the authentication or the provider refused the connection.";
					                break;
			                }
			                ?>
        <div class="error">
            <p><?php echo $message ?></p>
        </div><?php
			                $this->authorize_button();
		                }
		                //wp_redirect( admin_url('options-general.php?page='.$this->plugin_name.'&tab=authorize&calipsu_action&auth=success') );
		                ?>
        <div class="update">
            <p>Success, your site is now Connected to <?php echo $provider ?></p>
        </div><?php
	                }
	                break;
                default :
	                $this->authorize_button();

		    }
		}elseif( $active_tab == 'general' ){
			do_settings_sections( 'calipsu-social-post-general' );
		}
		?>
</div>