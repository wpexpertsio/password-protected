<?php

/**
 * Based roughly on wp-login.php @revision 19414
 * http://core.trac.wordpress.org/browser/trunk/wp-login.php?rev=19414
 */

global $Password_Protected, $error, $is_iphone;

/**
 * WP Shake JS
 */
if ( ! function_exists( 'wp_shake_js' ) ) {
	function wp_shake_js() {
		global $is_iphone;
		if ( $is_iphone )
			return;
		?>
		<script type="text/javascript">
		addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
		function s(id,pos){g(id).left=pos+'px';}
		function g(id){return document.getElementById(id).style;}
		function shake(id,a,d){c=a.shift();s(id,c);if(a.length>0){setTimeout(function(){shake(id,a,d);},d);}else{try{g(id).position='static';wp_attempt_focus();}catch(e){}}}
		addLoadEvent(function(){ var p=new Array(15,30,15,0,-15,-30,-15,0);p=p.concat(p.concat(p));var i=document.forms[0].id;g(i).position='relative';shake(i,p,20);});
		</script>
		<?php
	}
}

nocache_headers();
header( 'Content-Type: ' . get_bloginfo( 'html_type' ) . '; charset=' . get_bloginfo( 'charset' ) );

// Set a cookie now to see if they are supported by the browser.
setcookie( TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN );
if ( SITECOOKIEPATH != COOKIEPATH )
	setcookie( TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN );

// If cookies are disabled we can't log in even with a valid password.
if ( isset( $_POST['testcookie'] ) && empty( $_COOKIE[TEST_COOKIE] ) )
	$Password_Protected->errors->add( 'test_cookie', __( "<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. You must <a href='http://www.google.com/cookies.html'>enable cookies</a> to use WordPress.", 'password-protected' ) );

// Shake it!
$shake_error_codes = array( 'empty_password', 'incorrect_password' );
if ( $Password_Protected->errors->get_error_code() && in_array( $Password_Protected->errors->get_error_code(), $shake_error_codes ) )
	add_action( 'password_protected_login_head', 'wp_shake_js', 12 );

// Obey privacy setting
add_action( 'password_protected_login_head', 'noindex' );

/**
 * Support 3rd party plugins
 */
if ( class_exists( 'CWS_Login_Logo_Plugin' ) ) {
	// Add support for Mark Jaquith's Login Logo plugin
	// http://wordpress.org/extend/plugins/login-logo/
	add_action( 'password_protected_login_head', array( new CWS_Login_Logo_Plugin, 'login_head' ) );
} elseif ( class_exists( 'UberLoginLogo' ) ) {
	// Add support for Uber Login Logo plugin
	// http://wordpress.org/plugins/uber-login-logo/
	 add_action( 'password_protected_login_head', array( 'UberLoginLogo', 'replaceLoginLogo' ) );
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>

<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php echo apply_filters( 'password_protected_wp_title', get_bloginfo( 'name' ) ); ?></title>

<?php

global $wp_version;

if ( version_compare( $wp_version, '3.9-dev', '>=' ) ) {
	wp_admin_css( 'login', true );
} else {
	wp_admin_css( 'wp-admin', true );
	wp_admin_css( 'colors-fresh', true );
}

if ( $is_iphone ) {
	?>
	<meta name="viewport" content="width=320; initial-scale=0.9; maximum-scale=1.0; user-scalable=0;" />
	<style type="text/css" media="screen">
	.login form, .login .message, #login_error { margin-left: 0px; }
	.login #nav, .login #backtoblog { margin-left: 8px; }
	.login h1 a { width: auto; }
	#login { padding: 20px 0; }
	</style>
	<?php
}

do_action( 'login_enqueue_scripts' );
do_action( 'password_protected_login_head' );
?>

</head>
<body class="login login-password-protected login-action-password-protected-login wp-core-ui">

<div id="login">
	<h1><a href="<?php echo esc_url( apply_filters( 'password_protected_login_headerurl', home_url( '/' ) ) ); ?>" title="<?php echo esc_attr( apply_filters( 'password_protected_login_headertitle', get_bloginfo( 'name' ) ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
	<?php

	// Add message
	$message = apply_filters( 'password_protected_login_message', '' );
	if ( ! empty( $message ) ) echo $message . "\n";

	if ( $Password_Protected->errors->get_error_code() ) {
		$errors = '';
		$messages = '';
		foreach ( $Password_Protected->errors->get_error_codes() as $code ) {
			$severity = $Password_Protected->errors->get_error_data( $code );
			foreach ( $Password_Protected->errors->get_error_messages( $code ) as $error ) {
				if ( 'message' == $severity )
					$messages .= '	' . $error . "<br />\n";
				else
					$errors .= '	' . $error . "<br />\n";
			}
		}
		if ( ! empty( $errors ) )
			echo '<div id="login_error">' . apply_filters( 'password_protected_login_errors', $errors ) . "</div>\n";
		if ( ! empty( $messages ) )
			echo '<p class="message">' . apply_filters( 'password_protected_login_messages', $messages ) . "</p>\n";
	}
	?>

	<?php do_action( 'password_protected_before_login_form' ); ?>

	<form name="loginform" id="loginform" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post">
		<p>
			<label for="password_protected_pass"><?php _e( 'Password', 'password-protected' ) ?><br />
			<input type="password" name="password_protected_pwd" id="password_protected_pass" class="input" value="" size="20" tabindex="20" /></label>
		</p>
		<!--
		<p class="forgetmenot"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90"<?php checked( ! empty( $_POST['rememberme'] ) ); ?> /> <?php esc_attr_e( 'Remember Me', 'password-protected' ); ?></label></p>
		-->
		<p class="submit">
			<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Log In', 'password-protected' ); ?>" tabindex="100" />
			<input type="hidden" name="testcookie" value="1" />
			<input type="hidden" name="password-protected" value="login" />
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $_REQUEST['redirect_to'] ); ?>" />
		</p>
	</form>

	<?php do_action( 'password_protected_after_login_form' ); ?>

</div>

<script type="text/javascript">
try{document.getElementById('password_protected_pass').focus();}catch(e){}
if(typeof wpOnload=='function')wpOnload();
</script>

<?php do_action( 'login_footer' ); ?>

<div class="clear"></div>

</body>
</html>