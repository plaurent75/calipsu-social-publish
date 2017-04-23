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
	 * The hybrid config data.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $hybrid_conf    Hybrid config file path.
	 */
	private $hybrid_conf;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version,$hybrid_conf,$social_publish_options ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		/* Filter to allow Developer to set custom Conf file in theme or plugin */
		$this->hybrid_conf = $hybrid_conf;
		$this->social_publish_options = $social_publish_options;
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
			'Facebook' => [
				'field1' => __('Facebook App ID', 'calipsu-social-publish'),
				'field2' => __('Facebook App Secret', 'calipsu-social-publish'),
			],
			'Twitter' => [
				'field1' => __('Twitter Consumer Key', 'calipsu-social-publish'),
				'field2' => __('Twitter Consumer Secret', 'calipsu-social-publish'),
			],
			'LinkedIn' => [
				'field1' => __('LinkedIn ID Client', 'calipsu-social-publish'),
				'field2' => __('LinkedIn Secret Client', 'calipsu-social-publish'),
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
			$id1 = strtolower($network."_app");
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
				$id2 = strtolower($network."_secret");

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
		/*Page Select*/
		register_setting(
			'social_publish_page_group', // option_group
			'social_publish_page_name', // option_name
			[ $this, 'social_publish_page_sanitize' ] // sanitize_callback
		);

		add_settings_section(
			'calipsu_social_publish_page_section', // id
			'Settings', // title
			null, // callback
			'calipsu-social-publish' // page
		);

	}

	/**
	 * Display element
	 * Callback Function for add_settings_field
	 *
	 * @param array     $args   An array of arguments
	 * @since    1.0.0
	 */
	public function create_setting_field($args){
		$id     = strtolower($args['id']);
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

		foreach ($this->networks() as $network => $data){
			$id1 = strtolower($network."_app");
			if ( isset( $input[$id1] ) ) {
				$sanitary_values[$id1] = sanitize_text_field( $input[$id1] );
			}
			if(!empty($data['field2'])) {
				$id2 = strtolower($network."_secret");
				if ( isset( $input[$id2] ) ) {
					$sanitary_values[$id2] = sanitize_text_field( $input[$id2] );
				}
			}
		}


		return $sanitary_values;
	}
    public function get_config(){
	    if (!is_array($this->hybrid_conf)) {
		    $config = include $this->hybrid_conf;
	    }else{
		    $config = $this->hybrid_conf;
	    }
	    return $config;
    }
    public function is_enabled($provider){
        return $this->get_config()['providers'][$provider]['enabled'];
    }
	/**
	 * Generate Authorize Button
	 */
	public function authorize_button(){
		$config = $this->get_config();
		$cols = [
			'Provider','Account', 'Publish To', 'Action'
		];
		?><table class="widefat" cellspacing="0"><?php
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/calipsu-social-publish-table-display.php';
		//$url = $config['base_url'];
		?><tbody><?php
		foreach ($config['providers'] as $idpid => $params){
			if ($params['enabled']) {
				$firstKey= key($params['keys']);
				if( !empty( $params['keys'][$firstKey]) ) {
					if($this->is_connected_to('',$idpid )) {

						$url = wp_nonce_url( admin_url( 'options-general.php?page=' . $this->plugin_name . '&tab=authorize&calipsu_action=logout&provider=' . $idpid . '' ) );
						?>
						<tr style="vertical-align: middle">
						<td style="vertical-align: middle">
							Connected to <?php echo $idpid ?> Application.
						</td>
							<td style="vertical-align: middle"><?php $this->get_profile($idpid) ?></td>
							<td style="vertical-align: middle">
                                <?php
                                $pageoption = get_option('social_publish_page_name');
                                $key = $idpid.'_calipsu_publish_page';
                                if($params['pagechoice'] && $pageoption && array_key_exists($key,$pageoption)) {
	                                $currentPageId = $pageoption[$key];
	                                $hybridauth    = new Hybrid_Auth( $this->hybrid_conf );
	                                $adapter       = $hybridauth->authenticate( $idpid );
	                                switch ($idpid) {
                                        case 'Facebook':
	                                        $thepage       = $adapter->api()->get( $currentPageId,
		                                        $adapter->token( 'access_token' ) )->getDecodedBody();
	                                        $pageLink = 'https://www.facebook.com/'.$thepage['id'];
	                                        $img = 'https://graph.facebook.com/' .$thepage['id'] . '/picture?height=30&width=30';
                                            break;

                                        case 'LinkedIn':
                                            $thepage = false;
	                                        break;

                                        case 'Twitter':
                                            $thepage = false;
	                                        break;

                                        default:
                                            $thepage = false;

	                                }

                                    if($thepage) {
	                                    echo '<table><tr><td style="vertical-align: middle;"><img  src="' . $img . '" width="30" height="30"></td>';
	                                    echo '<td style="vertical-align: middle"><h3><a href="' . $pageLink . '" target="_blank">' . $thepage['name'] . '</a></h3></td></tr></table>';
                                    }
                                }elseif($params['pagechoice']){
                                ?>
                                    Select Where to publish:<br />
                                    <?php } ?>
                                <?php if($params['pagechoice']) { ?>
                                <p>
                                    <a href="#TB_inline?width=600&height=550&inlineId=calipsu_choice_<?php echo $idpid ?>" class="button thickbox">Select or Change</a>
                                </p>
                            <?php } ?>

							</td>
						<td style="vertical-align: middle">
								<a class="button button-secondary" href="<?php echo $url ?>">Disconnect Now</a>
						</td>
						</tr>
						<?php
					}else {
						$url = wp_nonce_url( admin_url( 'options-general.php?page=' . $this->plugin_name . '&tab=authorize&calipsu_action=authorize&provider=' . $idpid . '' ) );
						?>
						<tr class="alternate">
							<td class="column-columnname">
								Please Authorize <?php echo $idpid ?> Application.
							</td>
							<td>Your are not connected</td>
							<td>
								Your are not connected
							</td>
							<td class="column-columnname">
									<a class="button button-primary" href="<?php echo $url ?>">Authorize Now</a>
							</td>
						</tr>
						<?php
					}
				}else{

				}
			}
		}
		?></tbody></table><?php
		$this->generate_thickbox();

	}

	/**
	 * Check if user is connected to provider
	 */

	public function is_connected_to($hybridauth, $provider){
		if(empty($hybridauth)) $hybridauth = new Hybrid_Auth( $this->hybrid_conf );
		return $hybridauth->isConnectedWith($provider);
	}

	/**
	 * Get User profile data
	 */

	public function get_profile($provider){
			$hybridauth = new Hybrid_Auth( $this->hybrid_conf );
			$adapter = $hybridauth->authenticate($provider);
			$userProfile = $adapter->getUserProfile();
		?>
		<table>
			<tr>
				<td>
					<a href="<?php echo $userProfile->profileURL ?>" target="_blank">
						<img width="50px" height="50px" src="<?php echo $userProfile->photoURL ?>" />
					</a>

				</td>
				<td style="vertical-align: middle">
					Connected with : <a href="<?php echo $userProfile->profileURL ?>" target="_blank">
						<?php echo $userProfile->displayName ?>
					</a>
				</td>
			</tr>
		</table>

		<?php
		//$adapter->disconnect();

	}

	public function list_linkedin_page($linkedin){
	    //$linkedin->logout();
	    $api = $linkedin->api()->company('?is-company-admin=true&format=json',true);
		$accounts = [];
		if(array_key_exists('linkedin',$api)){
		    $data = $api['linkedin'];
		    $data = json_decode($data);
		    if($data->_total > 0){
	            foreach ($data->values as $id => $val){
		            $category = '';
		            $imgSquare = '';

		            $img = $linkedin->api()->company($val->id.':(square-logo-url,company-type)?format=json');
		            if(array_key_exists('linkedin',$img)){
                        $square = json_decode($img['linkedin']);
                        $imgSquare = $square->squareLogoUrl;
                        $category = $square->companyType->name;
		            }
	                $accounts[] = [
	                        'id' =>$val->id,
                            'name' => $val->name,
                            'category' => $category,
                            'img' => $imgSquare,
                            'pagelink' => '',
                    ];
                }
            }
        }
	   return $accounts;
    }

	/**
	 * List Page/Where to publish
	 */
	public function provider_publish_to($provider){
		$hybridauth = new Hybrid_Auth( $this->hybrid_conf );
		switch ($provider){
			case 'Facebook' :
				$facebook = $hybridauth->authenticate( $provider );
				$accounts = $facebook->getUserPages(true);
				break;

            case 'LinkedIn':
	            $linkedin = $hybridauth->authenticate( $provider );
	            $accounts=$this->list_linkedin_page($linkedin);
                break;
		}
		return [$accounts,$provider];
	}

	public function select_account_to_publish($accounts){
		$c=0;
		$compte = $accounts[0];
		$provider = $accounts[1];
		foreach ($compte as $data){
			$pageId = $data['id'];
			$pageName = $data['name'];
			if(!isset( $data['pagelink'])) $pageLink = 'https://www.facebook.com/'.$pageId;
			else $pageLink = $data['pagelink'];
			if(!isset( $data['img'])) $img = 'https://graph.facebook.com/' .$pageId . '/picture?type=square';
			else $img = $data['img'];
			$cat = $data['category'];
			$dataPage      = [
				'id'   => $pageId,
				'img'  => $img,
				'name' => $pageName,
				'url'  => $pageLink,
				'category' => $cat,
                'provider' => $provider,
			];
			if($c % 2 == 0) echo '<tr class="'.$c.'">';
			?>

					<?php $this->generate_table_account( $dataPage ) ?>
			<?php
			if($c % 2 == 1) echo '</tr>';

			$c++;
		}
		if($c % 2 == 1) echo '</tr>';
	}
	public function mapData($provider){
		$hybridauth = new Hybrid_Auth( $this->hybrid_conf );
		$adapter = $hybridauth->authenticate($provider);
		$userProfile = $adapter->getUserProfile();
		//var_dump($userProfile);

	}
	public function generate_provider_data($hybridauth,$provider){
		$adapter     = $hybridauth->authenticate( $provider );
		$userProfile = $adapter->getUserProfile();
		$data      = [
			'id'       => $userProfile->identifier,
			'img'      => $userProfile->photoURL,
			'name'     => $userProfile->displayName,
			'url'      => $userProfile->profileURL,
			'category' => '',
            'provider' => $provider,
		];
		return $data;
    }
	public function generate_thickbox(){
		$hybridauth = new Hybrid_Auth( $this->hybrid_conf );
		$connectedTo = $hybridauth->getConnectedProviders();
		if(count($connectedTo)>0) :
		foreach ($connectedTo as $provider) {
			if($this->is_enabled($provider)) {
				$dataMe      = $this->generate_provider_data($hybridauth,$provider);
				?>
                <div id="calipsu_choice_<?php echo $provider ?>" style="display: none">
                    <form method="post" action="options.php">
                        <?php
                        settings_fields( 'social_publish_page_group' );
                        do_settings_sections( 'calipsu-social-publish-page' );
		                ?>
                    <p>Publish to my <strong><?php $provider ?> Wall</strong>&nbsp;:</p>
                    <table id="option-profile">
                        <tbody>
                        <tr>
							<?php $this->generate_table_account( $dataMe ) ?>
                        </tr>
                        </tbody>
                    </table>
                    <p>Publish to a <?php echo $provider ?> Page:</p>
                    <table id="option-fb-fanpage">
                        <tbody>
						<?php $this->select_account_to_publish( $this->provider_publish_to( $provider ) ) ?>
                        </tbody>
                    </table>
                        <?php submit_button(); ?>
                    </form>
                </div>
				<?php
			}
		}
		endif;
	}
	public function generate_table_account($data){
	    $pageoption = get_option('social_publish_page_name');
	    $provider = $data['provider'];
		?>
		<?php $checked = ( isset( $pageoption[$provider.'_calipsu_publish_page'] ) && $pageoption[$provider.'_calipsu_publish_page'] === $data['id'] ) ? 'checked' : '' ; ?>

				<td class="radio">
					<input name="social_publish_page_name[<?php echo $provider ?>_calipsu_publish_page]" type="radio" id="<?php echo $data['id'] ?>" value="<?php echo $data['id'] ?>" <?php echo $checked ?>>
				</td>
				<td class="thumbnail">
					<label for="<?php echo $data['id'] ?>">
						<img src="<?php echo $data['img'] ?>" width="50" height="50">
					</label>
				</td>
				<td class="details">
					<label for="<?php echo esc_attr( $data['id'] ) ?>">
						<span class="name"><?php echo esc_html( $data['name'] ) ?></span><br/>
						<?php if($data['category']) { ?><span class="description category"><small><?php echo esc_html( $data['category'] ) ?></small></span><?php } ?>
					</label>
				</td>

<?php
	}
	/**
	 * Sanitize Callback Function for Page
	 *
	 * @param array $input
	 *
	 * @return array $sanitary_values
	 *
	 * @since    1.0.0
	 */
	public function social_publish_page_sanitize($input) {
		$sanitary_values = array();
		foreach ($this->networks() as $network => $data){
			$id = $network."_calipsu_publish_page";
			if ( isset( $input[$id] ) ) {
				$sanitary_values[$id] = sanitize_text_field( $input[$id] );
			}
		}

		return $sanitary_values;
	}
}
