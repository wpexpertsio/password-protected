<?php
/*
Plugin Name: Password Protected
Plugin URI: https://wordpress.org/plugins/password-protected/
Description: A very simple way to quickly password protect your WordPress site with a single password. Please note: This plugin does not restrict access to uploaded files and images and does not work with some caching setups.
Version: 2.5.3
Author: WPExperts
Text Domain: password-protected
Author URI: https://wpexperts.io/
License: GPLv2
*/

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * @todo Use wp_hash_password() ?
 * @todo Remember me
 */

define( 'PASSWORD_PROTECTED_SUBDIR', '/' . str_replace( basename( __FILE__ ), '', plugin_basename( __FILE__ ) ) );
define( 'PASSWORD_PROTECTED_URL', plugins_url( PASSWORD_PROTECTED_SUBDIR ) );
define( 'PASSWORD_PROTECTED_DIR', plugin_dir_path( __FILE__ ) );

global $Password_Protected;
$Password_Protected = new Password_Protected();

class Password_Protected {

	var $version = '2.5.3';
	var $admin   = null;
	var $errors  = null;

	/**
	 * Constructor
	 */
	public function __construct() {

		$this->errors = new WP_Error();

		register_activation_hook( __FILE__, array( &$this, 'install' ) );

		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

		add_filter( 'password_protected_is_active', array( $this, 'allow_ip_addresses' ) );

		add_action( 'init', array( $this, 'disable_caching' ), 1 );
		add_action( 'init', array( $this, 'maybe_process_logout' ), 1 );
		add_action( 'init', array( $this, 'maybe_process_login' ), 1 );
		add_action( 'wp', array( $this, 'disable_feeds' ) );
		add_action( 'template_redirect', array( $this, 'maybe_show_login' ), -1 );
		add_filter( 'pre_option_password_protected_status', array( $this, 'allow_feeds' ) );
		add_filter( 'pre_option_password_protected_status', array( $this, 'allow_administrators' ) );
		add_filter( 'pre_option_password_protected_status', array( $this, 'allow_users' ) );
		add_filter( 'rest_authentication_errors', array( $this, 'only_allow_logged_in_rest_access' ) );
		add_action( 'init', array( $this, 'compat' ) );
		add_action( 'password_protected_login_messages', array( $this, 'login_messages' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'load_theme_stylesheet' ), 5 );

		// Available from WordPress 4.3+
		if ( function_exists( 'wp_site_icon' ) ) {
			add_action( 'password_protected_login_head', 'wp_site_icon' );
		}

		add_shortcode( 'password_protected_logout_link', array( $this, 'logout_link_shortcode' ) );

		include_once dirname( __FILE__ ) . '/admin/admin-bar.php';

		if ( is_admin() ) {

			include_once dirname( __FILE__ ) . '/admin/admin-caching.php';
			include_once dirname( __FILE__ ) . '/admin/admin.php';

			$this->admin_caching = new Password_Protected_Admin_Caching( $this );
			$this->admin         = new Password_Protected_Admin();

		}

	}

	/**
	 * I18n
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( 'password-protected', false, basename( dirname( __FILE__ ) ) . '/languages' );

	}

	/**
	 * Disable Page Caching
	 */
	public function disable_caching() {

		if ( $this->is_active() && ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true );
		}

	}

	/**
	 * Is Active?
	 *
	 * @return  boolean  Is password protection active?
	 */
	public function is_active() {

		global $wp_query;

		// Always allow access to robots.txt
		if ( isset( $wp_query ) && is_robots() ) {
			return false;
		}

		if ( (bool) get_option( 'password_protected_status' ) ) {
			$is_active = true;
		} else {
			$is_active = false;
		}

		$is_active = apply_filters( 'password_protected_is_active', $is_active );

		if ( isset( $_GET['password-protected'] ) ) {
			$is_active = true;
		}

		return $is_active;

	}

	/**
	 * Disable Feeds
	 *
	 * @todo  An option/filter to prevent disabling of feeds.
	 */
	public function disable_feeds() {

		if ( $this->is_active() ) {
			add_action( 'do_feed', array( $this, 'disable_feed' ), 1 );
			add_action( 'do_feed_rdf', array( $this, 'disable_feed' ), 1 );
			add_action( 'do_feed_rss', array( $this, 'disable_feed' ), 1 );
			add_action( 'do_feed_rss2', array( $this, 'disable_feed' ), 1 );
			add_action( 'do_feed_atom', array( $this, 'disable_feed' ), 1 );
		}

	}

	/**
	 * Disable Feed
	 *
	 * @todo  Make Translatable
	 */
	public function disable_feed() {

		wp_die( sprintf( __( 'Feeds are not available for this site. Please visit the <a href="%s">website</a>.', 'password-protected' ), get_bloginfo( 'url' ) ) );

	}

	/**
	 * Allow Feeds
	 *
	 * @param   boolean $bool  Allow feeds.
	 * @return  boolean         True/false.
	 */
	public function allow_feeds( $bool ) {

		if ( is_feed() && (bool) get_option( 'password_protected_feeds' ) ) {
			return 0;
		}

		return $bool;

	}

	/**
	 * Allow Administrators
	 *
	 * @param   boolean $bool  Allow administrators.
	 * @return  boolean         True/false.
	 */
	public function allow_administrators( $bool ) {

		if ( ! is_admin() && current_user_can( 'manage_options' ) && (bool) get_option( 'password_protected_administrators' ) ) {
			return 0;
		}

		return $bool;

	}

	/**
	 * Allow Users
	 *
	 * @param   boolean $bool  Allow administrators.
	 * @return  boolean         True/false.
	 */
	public function allow_users( $bool ) {

		if ( ! is_admin() && is_user_logged_in() && (bool) get_option( 'password_protected_users' ) ) {
			return 0;
		}

		return $bool;

	}

	/**
	 * Allow IP Addresses
	 *
	 * If user has a valid email address, return false to disable password protection.
	 *
	 * @param   boolean $bool  Allow IP addresses.
	 * @return  boolean         True/false.
	 */
	public function allow_ip_addresses( $bool ) {

		$ip_addresses = $this->get_allowed_ip_addresses();

		if ( isset( $_SERVER['REMOTE_ADDR'] ) && in_array( $_SERVER['REMOTE_ADDR'], $ip_addresses ) ) {
			$bool = false;
		}

		return $bool;

	}

	/**
	 * Get Allowed IP Addresses
	 *
	 * @return  array  IP addresses.
	 */
	public function get_allowed_ip_addresses() {

		return explode( "\n", get_option( 'password_protected_allowed_ip_addresses' ) );

	}

	/**
	 * Allow the remember me function
	 *
	 * @return. boolean
	 */
	public function allow_remember_me() {

		return (bool) get_option( 'password_protected_remember_me' );

	}

	/**
	 * Encrypt Password
	 *
	 * @param  string $password  Password.
	 * @return string             Encrypted password.
	 */
	public function encrypt_password( $password ) {

		return md5( $password );

	}

	/**
	 * Maybe Process Logout
	 */
	public function maybe_process_logout() {

		if ( isset( $_REQUEST['password-protected'] ) && $_REQUEST['password-protected'] == 'logout' ) {

			$this->logout();

			if ( isset( $_REQUEST['redirect_to'] ) ) {
				$redirect_to = remove_query_arg( 'password-protected', esc_url_raw( $_REQUEST['redirect_to'], array( 'http', 'https' ) ) );
			} else {
				$redirect_to = home_url( '/' );
			}

			$this->safe_redirect( $redirect_to );
			exit();

		}

	}

	/**
	 * Maybe Process Login
	 */
	public function maybe_process_login() {

		if ( $this->is_active() && isset( $_REQUEST['password_protected_pwd'] ) ) {
			$password_protected_pwd = $_REQUEST['password_protected_pwd'];
			$pwd                    = get_option( 'password_protected_password' );

			// If correct password...
			if ( ( hash_equals( $pwd, $this->encrypt_password( $password_protected_pwd ) ) && $pwd != '' ) || apply_filters( 'password_protected_process_login', false, $password_protected_pwd ) ) {

				$remember = isset( $_REQUEST['password_protected_rememberme'] ) ? boolval( $_REQUEST['password_protected_rememberme'] ) : false;

				if ( ! $this->allow_remember_me() ) {
					$remember = false;
				}

				$this->set_auth_cookie( $remember );
				$redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
				$redirect_to = apply_filters( 'password_protected_login_redirect', $redirect_to );

				if ( ! empty( $redirect_to ) ) {
					$this->safe_redirect( remove_query_arg( 'password-protected', $redirect_to ) );
					exit;
				} elseif ( isset( $_GET['password_protected_pwd'] ) ) {
					$this->safe_redirect( remove_query_arg( 'password-protected' ) );
					exit;
				}
			} else {

				// ... otherwise incorrect password
				$this->clear_auth_cookie();
				$this->errors->add( 'incorrect_password', __( 'Incorrect Password', 'password-protected' ) );

			}
		}

	}

	/**
	 * Is User Logged In?
	 *
	 * @return  boolean
	 */
	public function is_user_logged_in() {

		return $this->is_active() && $this->validate_auth_cookie();

	}

	/**
	 * Maybe Show Login
	 */
	public function maybe_show_login() {
		if ( in_array( 'login-designer/login-designer.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			if ( is_customize_preview() ) {
				return;
			}
		}

		// Filter for adding exceptions.
		$show_login = apply_filters( 'password_protected_show_login', $this->is_active() );

		// Logged in
		if ( $this->is_user_logged_in() ) {
			$show_login = false;
		}

		if ( ! $show_login ) {
			return;
		}

		// Show login form
		if ( isset( $_REQUEST['password-protected'] ) && 'login' == $_REQUEST['password-protected'] ) {

			$default_theme_file = locate_template( array( 'password-protected-login.php' ) );

			if ( empty( $default_theme_file ) ) {
				$default_theme_file = dirname( __FILE__ ) . '/theme/password-protected-login.php';
			}

			$theme_file = apply_filters( 'password_protected_theme_file', $default_theme_file );
			if ( ! file_exists( $theme_file ) ) {
				$theme_file = $default_theme_file;
			}

			load_template( $theme_file );
			exit();

		} else {

			$redirect_to = add_query_arg( 'password-protected', 'login', home_url() );

			// URL to redirect back to after login
			$redirect_to_url = apply_filters( 'password_protected_login_redirect_url', ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
			if ( ! empty( $redirect_to_url ) ) {
				$redirect_to = add_query_arg( 'redirect_to', urlencode( $redirect_to_url ), $redirect_to );
			}

			nocache_headers();
			wp_redirect( $redirect_to );
			exit();

		}
	}

	/**
	 * Get Site ID
	 *
	 * @return  string  Site ID.
	 */
	public function get_site_id() {

		global $blog_id;
		return 'bid_' . apply_filters( 'password_protected_blog_id', $blog_id );

	}

	/**
	 * Login URL
	 *
	 * @return  string  Login URL.
	 */
	public function login_url() {

		return add_query_arg( 'password-protected', 'login', home_url( '/' ) );

	}

	/**
	 * Logout
	 */
	public function logout() {

		$this->clear_auth_cookie();
		do_action( 'password_protected_logout' );

	}

	/**
	 * Logout URL
	 *
	 * @param   string $redirect_to  Optional. Redirect URL.
	 * @return  string                Logout URL.
	 */
	public function logout_url( $redirect_to = '' ) {

		$query = array(
			'password-protected' => 'logout',
			'redirect_to'        => esc_url_raw( $redirect_to ),
		);

		if ( empty( $query['redirect_to'] ) ) {
			unset( $query['redirect_to'] );
		}

		return add_query_arg( $query, home_url() );

	}

	/**
	 * Logout Link
	 *
	 * @param   array $args  Link args.
	 * @return  string         HTML link tag.
	 */
	public function logout_link( $args = null ) {

		// Only show if user is logged in
		if ( ! $this->is_user_logged_in() ) {
			return '';
		}

		$args = wp_parse_args(
			$args,
			array(
				'redirect_to' => '',
				'text'        => __( 'Logout', 'password-protected' ),
			)
		);

		if ( empty( $args['text'] ) ) {
			$args['text'] = __( 'Logout', 'password-protected' );
		}

		return sprintf( '<a href="%s">%s</a>', esc_url( $this->logout_url( $args['redirect_to'] ) ), esc_html( $args['text'] ) );

	}

	/**
	 * Logout Link Shortcode
	 *
	 * @param   array $args  Link args.
	 * @return  string         HTML link tag.
	 */
	public function logout_link_shortcode( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'redirect_to' => '',
				'text'        => $content,
			),
			$atts,
			'logout_link_shortcode'
		);

		return $this->logout_link( $atts );

	}

	/**
	 * Get Hashed Password
	 *
	 * @return  string  Hashed password.
	 */
	public function get_hashed_password() {

		return md5( get_option( 'password_protected_password' ) . wp_salt() );

	}

	/**
	 * Validate Auth Cookie
	 *
	 * @param   string $cookie  Cookie string.
	 * @param   string $scheme  Cookie scheme.
	 * @return  boolean           Validation successful?
	 */
	public function validate_auth_cookie( $cookie = '', $scheme = '' ) {

		if ( ! $cookie_elements = $this->parse_auth_cookie( $cookie, $scheme ) ) {
			do_action( 'password_protected_auth_cookie_malformed', $cookie, $scheme );
			return false;
		}

		extract( $cookie_elements, EXTR_OVERWRITE );

		$expired = $expiration;

		// Allow a grace period for POST and AJAX requests
		if ( defined( 'DOING_AJAX' ) || 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			$expired += 3600;
		}

		// Quick check to see if an honest cookie has expired
		if ( $expired < current_time( 'timestamp' ) ) {
			do_action( 'password_protected_auth_cookie_expired', $cookie_elements );
			return false;
		}

		$key  = md5( $this->get_site_id() . $this->get_hashed_password() . '|' . $expiration );
		$hash = hash_hmac( 'md5', $this->get_site_id() . '|' . $expiration, $key );

		if ( $hmac != $hash ) {
			do_action( 'password_protected_auth_cookie_bad_hash', $cookie_elements );
			return false;
		}

		if ( $expiration < current_time( 'timestamp' ) ) { // AJAX/POST grace period set above
			$GLOBALS['login_grace_period'] = 1;
		}

		return true;

	}

	/**
	 * Generate Auth Cookie
	 *
	 * @param   int    $expiration  Expiration time in seconds.
	 * @param   string $scheme      Cookie scheme.
	 * @return  string               Cookie.
	 */
	public function generate_auth_cookie( $expiration, $scheme = 'auth' ) {

		$key    = md5( $this->get_site_id() . $this->get_hashed_password() . '|' . $expiration );
		$hash   = hash_hmac( 'md5', $this->get_site_id() . '|' . $expiration, $key );
		$cookie = $this->get_site_id() . '|' . $expiration . '|' . $hash;

		return $cookie;

	}

	/**
	 * Parse Auth Cookie
	 *
	 * @param   string $cookie  Cookie string.
	 * @param   string $scheme  Cookie scheme.
	 * @return  string           Cookie string.
	 */
	public function parse_auth_cookie( $cookie = '', $scheme = '' ) {

		if ( empty( $cookie ) ) {

			$cookie_name = $this->cookie_name();

			if ( empty( $_COOKIE[ $cookie_name ] ) ) {
				return false;
			}

			$cookie = $_COOKIE[ $cookie_name ];

		}

		$cookie_elements = explode( '|', $cookie );

		if ( count( $cookie_elements ) != 3 ) {
			return false;
		}

		list( $site_id, $expiration, $hmac ) = $cookie_elements;

		return compact( 'site_id', 'expiration', 'hmac', 'scheme' );

	}

	/**
	 * Set Auth Cookie
	 *
	 * @todo
	 *
	 * @param  boolean $remember  Remember logged in.
	 * @param  string  $secure    Secure cookie.
	 */
	public function set_auth_cookie( $remember = false, $secure = '' ) {

		if ( $remember ) {
			$expiration_time = apply_filters( 'password_protected_auth_cookie_expiration', get_option( 'password_protected_remember_me_lifetime', 14 ) * DAY_IN_SECONDS, $remember );
			$expiration      = $expire = current_time( 'timestamp' ) + $expiration_time;
		} else {
			$expiration_time = apply_filters( 'password_protected_auth_cookie_expiration', DAY_IN_SECONDS * 20, $remember );
			$expiration      = current_time( 'timestamp' ) + $expiration_time;
			$expire          = 0;
		}

		if ( '' === $secure ) {
			$secure = is_ssl();
		}

		$secure_password_protected_cookie = apply_filters( 'password_protected_secure_password_protected_cookie', false, $secure );
		$password_protected_cookie        = $this->generate_auth_cookie( $expiration, 'password_protected' );

		setcookie( $this->cookie_name(), $password_protected_cookie, $expire, COOKIEPATH, COOKIE_DOMAIN, $secure_password_protected_cookie, true );
		if ( COOKIEPATH != SITECOOKIEPATH ) {
			setcookie( $this->cookie_name(), $password_protected_cookie, $expire, SITECOOKIEPATH, COOKIE_DOMAIN, $secure_password_protected_cookie, true );
		}

	}

	/**
	 * Clear Auth Cookie
	 */
	public function clear_auth_cookie() {

		setcookie( $this->cookie_name(), ' ', current_time( 'timestamp' ) - 31536000, COOKIEPATH, COOKIE_DOMAIN );
		setcookie( $this->cookie_name(), ' ', current_time( 'timestamp' ) - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN );

	}

	/**
	 * Cookie Name
	 *
	 * @return  string  Cookie name.
	 */
	public function cookie_name() {

		/**
		 * Filters the cookie name
		 */
		return apply_filters( 'password_protected_cookie_name', $this->get_site_id() . '_password_protected_auth', $this );

	}

	/**
	 * Install
	 */
	public function install() {

		$old_version = get_option( 'password_protected_version' );

		// 1.1 - Upgrade to MD5
		if ( empty( $old_version ) || version_compare( '1.1', $old_version ) ) {
			$pwd = get_option( 'password_protected_password' );
			if ( ! empty( $pwd ) ) {
				$new_pwd = $this->encrypt_password( $pwd );
				update_option( 'password_protected_password', $new_pwd );
			}
		}

		update_option( 'password_protected_version', $this->version );

	}

	/**
	 * Compat
	 *
	 * Support for 3rd party plugins:
	 *
	 * - Login Logo       https://wordpress.org/plugins/login-logo/
	 * - Uber Login Logo  https://wordpress.org/plugins/uber-login-logo/
	 */
	public function compat() {

		if ( class_exists( 'CWS_Login_Logo_Plugin' ) ) {

			// Add support for Mark Jaquith's Login Logo plugin
			add_action( 'password_protected_login_head', array( new CWS_Login_Logo_Plugin(), 'login_head' ) );

		} elseif ( class_exists( 'UberLoginLogo' ) ) {

			// Add support for Uber Login Logo plugin
			add_action( 'password_protected_login_head', array( 'UberLoginLogo', 'replaceLoginLogo' ) );

		}

	}

	/**
	 * Login Messages
	 * Outputs messages and errors in the login template.
	 */
	public function login_messages() {

		// Add message
		$message = apply_filters( 'password_protected_login_message', '' );
		if ( ! empty( $message ) ) {
			echo $message . "\n";
		}

		if ( $this->errors->get_error_code() ) {

			$errors   = '';
			$messages = '';

			foreach ( $this->errors->get_error_codes() as $code ) {
				$severity = $this->errors->get_error_data( $code );
				foreach ( $this->errors->get_error_messages( $code ) as $error ) {
					if ( 'message' == $severity ) {
						$messages .= $error . '<br />';
					} else {
						$errors .= $error . '<br />';
					}
				}
			}

			if ( ! empty( $errors ) ) {
				echo '<div id="login_error">' . apply_filters( 'password_protected_login_errors', $errors ) . "</div>\n";
			}
			if ( ! empty( $messages ) ) {
				echo '<p class="message">' . apply_filters( 'password_protected_login_messages', $messages ) . "</p>\n";
			}
		}

	}

	/**
	 * Load Theme Stylesheet
	 *
	 * Check wether a 'password-protected-login.css' stylesheet exists in your theme
	 * and if so loads it.
	 *
	 * Works with child themes.
	 *
	 * Possible to specify a different file in the theme folder via the
	 * 'password_protected_stylesheet_file' filter (allows for theme subfolders).
	 */
	public function load_theme_stylesheet() {

		$filename = apply_filters( 'password_protected_stylesheet_file', 'password-protected-login.css' );

		$located = locate_template( $filename );

		if ( ! empty( $located ) ) {

			$stylesheet_directory = trailingslashit( get_stylesheet_directory() );
			$template_directory   = trailingslashit( get_template_directory() );

			if ( $stylesheet_directory == substr( $located, 0, strlen( $stylesheet_directory ) ) ) {
				wp_enqueue_style( 'password-protected-login', get_stylesheet_directory_uri() . '/' . $filename );
			} elseif ( $template_directory == substr( $located, 0, strlen( $template_directory ) ) ) {
				wp_enqueue_style( 'password-protected-login', get_template_directory_uri() . '/' . $filename );
			}
		}

	}

	/**
	 * Safe Redirect
	 *
	 * Ensure the redirect is to the same site or pluggable list of allowed domains.
	 * If invalid will redirect to ...
	 * Based on the WordPress wp_safe_redirect() function.
	 */
	public function safe_redirect( $location, $status = 302 ) {

		$location = wp_sanitize_redirect( $location );
		$location = wp_validate_redirect( $location, home_url() );

		wp_redirect( $location, $status );

	}

	/**
	 * Is Plugin Supported?
	 *
	 * Check to see if there are any known reasons why this plugin may not work in
	 * the user's hosting environment.
	 *
	 * @return  boolean
	 */
	static function is_plugin_supported() {

		return true;

	}

	/**
	 * Check whether a given request has permissions
	 *
	 * Always allow logged in users who require REST API for Gutenberg
	 * and other admin/plugin compatibility.
	 *
	 * @param   WP_REST_Request $access  Full details about the request.
	 * @return  WP_Error|boolean
	 */
	public function only_allow_logged_in_rest_access( $access ) {

		// If user is not logged in
		if ( $this->is_active() && ! $this->is_user_logged_in() && ! is_user_logged_in() && ! (bool) get_option( 'password_protected_rest' ) ) {
			return new WP_Error( 'rest_cannot_access', __( 'Only authenticated users can access the REST API.', 'password-protected' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return $access;

	}

}
