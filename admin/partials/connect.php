<?php
if( isset( $_GET["provider"] ) ){
	$provider = @ trim( strip_tags( $_GET["provider"] ) );
	try {
		$o  = $this->social_publish_options;
		$ha = new Hybrid_Auth( $this->hybrid_conf );
		$f = $ha->authenticate('Facebook');
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
				$message = "It seems you forgot yo mention provider application credentials.";
				break;
			case 5 :
				$message = "Authentication has failed. Either the user has canceled the authentication or the provider refused the connection.";
				break;
			case 701 :
				$message = "Authentication has failed. Either the user has canceled the authentication or the provider refused the connection.";
				break;
		}
	}
}
else
{
	?>
	<p>There was some unexpected error, when trying to login with <?php echo $provider; ?></p>
	<?php
}