<?php

/*
Plugin Name: Password Protected
Plugin URI: http://wordpress.org/extend/plugins/password-protected/
Description: A very simple way to quickly password protect your WordPress site with a single password. Please note: This plugin does not restrict access to uploaded files and images and does not work on WP Engine or with some caching setups.
Version: 1.7.2
Author: Ben Huson
Text Domain: password-protected
Author URI: http://github.com/benhuson/password-protected/
License: GPLv2
*/

/*
Copyright 2012 Ben Huson (email : ben@thewhiteroom.net)

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

	var $version = '1.7.1';
	var $admin   = null;
	var $errors  = null;

	/**
	 * Constructor
	 */
	function Password_Protected() {
		$this->errors = new WP_Error();
		register_activation_hook( __FILE__, array( &$this, 'install' ) );
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'init', array( $this, 'disable_caching' ), 1 );
		add_action( 'init', array( $this, 'maybe_process_login' ), 1 );
		add_action( 'wp', array( $this, 'disable_feeds' ) );
		add_action( 'template_redirect', array( $this, 'maybe_show_login' ), 1 );
		add_filter( 'pre_option_password_protected_status', array( $this, 'allow_feeds' ) );
		add_filter( 'pre_option_password_protected_status', array( $this, 'allow_administrators' ) );
		add_filter( 'pre_option_password_protected_status', array( $this, 'allow_users' ) );
		if ( is_admin() ) {
			include_once( dirname( __FILE__ ) . '/admin/admin.php' );
			$this->admin = new Password_Protected_Admin();
		}
	}

	/**
	 * I18n
	 */
	function load_plugin_textdomain() {
		load_plugin_textdomain( 'password-protected', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Disable Page Caching
	 */
	function disable_caching() {
		if ( $this->is_active() && ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true );
		}	
	}

	/**
	 * Is Active?
	 *
	 * @return  boolean  Is password protection active?
	 */
	function is_active() {
		global $wp_query;

		// Always allow access to robots.txt
		if ( isset( $wp_query ) && is_robots() ) {
			return false;
		}

		if ( (bool) get_option( 'password_protected_status' ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Disable Feeds
	 *
	 * @todo  An option/filter to prevent disabling of feeds.
	 */
	function disable_feeds() {
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
	function disable_feed() {
		wp_die( sprintf( __( 'Feeds are not available for this site. Please visit the <a href="%s">website</a>.', 'password-protected' ), get_bloginfo( 'url' ) ) );
	}

	/**
	 * Allow Feeds
	 *
	 * @param   boolean  $bool  Allow feeds.
	 * @return  boolean         True/false.
	 */
	function allow_feeds( $bool ) {
		if ( is_feed() && (bool) get_option( 'password_protected_feeds' ) ) {
			return 0;
		}
		return $bool;
	}

	/**
	 * Allow Administrators
	 *
	 * @param   boolean  $bool  Allow administrators.
	 * @return  boolean         True/false.
	 */
	function allow_administrators( $bool ) {
		if ( ! is_admin() && current_user_can( 'manage_options' ) && (bool) get_option( 'password_protected_administrators' ) ) {
			return 0;
		}
		return $bool;
	}

	/**
	 * Allow Users
	 *
	 * @param   boolean  $bool  Allow administrators.
	 * @return  boolean         True/false.
	 */
	function allow_users( $bool ) {
		if ( ! is_admin() && current_user_can( 'manage_options' ) && (bool) get_option( 'password_protected_users' ) ) {
			return 0;
		}
		return $bool;
	}

	/**
	 * Encrypt Password
	 *
	 * @param  string  $password  Password.
	 * @return string             Encrypted password.
	 */
	function encrypt_password( $password ) {
		return md5( $password );
	}

	/**
	 * Maybe Process Login
	 */
	function maybe_process_login() {
		if ( $this->is_active() && isset( $_REQUEST['password_protected_pwd'] ) ) {
			$password_protected_pwd = $_REQUEST['password_protected_pwd'];
			$pwd = get_option( 'password_protected_password' );
			// If correct password...
			if ( ( $this->encrypt_password( $password_protected_pwd ) == $pwd && $pwd != '' ) || apply_filters( 'password_protected_process_login', false, $password_protected_pwd ) ) {
				$this->set_auth_cookie();
				$redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
				$redirect_to = apply_filters( 'password_protected_login_redirect', $redirect_to );
				if ( ! empty( $redirect_to ) ) {
					$this->safe_redirect( $redirect_to );
					exit;
				}
			} else {
				// ... otherwise incorrect password
				$this->clear_auth_cookie();
				$this->errors->add( 'incorrect_password', __( 'Incorrect Password', 'password-protected' ) );
			}
		}

		// Log out
		if ( isset( $_REQUEST['password-protected'] ) && $_REQUEST['password-protected'] == 'logout' ) {
			$this->logout();
			if ( isset( $_REQUEST['redirect_to'] ) ) {
				$redirect_to = esc_url_raw( $_REQUEST['redirect_to'], array( 'http', 'https' ) );
				wp_redirect( $redirect_to );
				exit();
			}
			$redirect_to = remove_query_arg( array( 'password-protected', 'redirect_to' ), ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
			$query = array(
				'password-protected' => 'login',
				'redirect_to' => urlencode( $redirect_to )
			);
			wp_redirect( add_query_arg( $query, home_url() ) );
			exit();
		}
	}

	/**
	 * Maybe Show Login
	 */
	function maybe_show_login() {

		// Don't show login if not enabled
		if ( ! $this->is_active() ) {
			return;
		}

		// Logged in
		if ( $this->validate_auth_cookie() ) {
			return;
		}

		// Show login form
		if ( isset( $_REQUEST['password-protected'] ) && 'login' == $_REQUEST['password-protected'] ) {
			$default_theme_file = dirname( __FILE__ ) . '/theme/login.php';
			$theme_file = apply_filters( 'password_protected_theme_file', $default_theme_file );
			if ( ! file_exists( $theme_file ) ) {
				$theme_file = $default_theme_file;
			}
			include( $theme_file );
			exit();
		} else {
			$query = array(
				'password-protected' => 'login',
				'redirect_to' => urlencode( ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] )
			);
			wp_redirect( add_query_arg( $query, home_url() ) );
			exit();
		}
	}

	/**
	 * Get Site ID
	 *
	 * @return  string  Site ID.
	 */
	function get_site_id() {
		global $blog_id;
		return 'bid_' . apply_filters( 'password_protected_blog_id', $blog_id );
	}

	/**
	 * Logout
	 */
	function logout() {
		$this->clear_auth_cookie();
		do_action( 'password_protected_logout' );
	}

	/**
	 * Validate Auth Cookie
	 *
	 * @param   string   $cookie  Cookie string.
	 * @param   string   $scheme  Cookie scheme.
	 * @return  boolean           Validation successful?
	 */
	function validate_auth_cookie( $cookie = '', $scheme = '' ) {
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
		if ( $expired < time() ) {
			do_action('password_protected_auth_cookie_expired', $cookie_elements);
			return false;
		}

		$pass = md5( get_option( 'password_protected_password' ) );
		$pass_frag = substr( $pass, 8, 4 );

		$key = md5( $this->get_site_id() . $pass_frag . '|' . $expiration );
		$hash = hash_hmac( 'md5', $this->get_site_id() . '|' . $expiration, $key);

		if ( $hmac != $hash ) {
			do_action( 'password_protected_auth_cookie_bad_hash', $cookie_elements );
			return false;
		}

		if ( $expiration < time() ) { // AJAX/POST grace period set above
			$GLOBALS['login_grace_period'] = 1;
		}

		return true;
	}

	/**
	 * Generate Auth Cookie
	 *
	 * @param   int     $expiration  Expiration time in seconds.
	 * @param   string  $scheme      Cookie scheme.
	 * @return  string               Cookie.
	 */
	function generate_auth_cookie( $expiration, $scheme = 'auth' ) {
		$pass = md5( get_option( 'password_protected_password' ) );
		$pass_frag = substr( $pass, 8, 4 );

		$key = md5( $this->get_site_id() . $pass_frag . '|' . $expiration );
		$hash = hash_hmac( 'md5', $this->get_site_id() . '|' . $expiration, $key );
		$cookie = $this->get_site_id() . '|' . $expiration . '|' . $hash;

		return $cookie;
	}

	/**
	 * Parse Auth Cookie
	 *
	 * @param   string  $cookie  Cookie string.
	 * @param   string  $scheme  Cookie scheme.
	 * @return  string           Cookie string.
	 */
	function parse_auth_cookie( $cookie = '', $scheme = '' ) {
		if ( empty( $cookie ) ) {
			$cookie_name = $this->cookie_name();
	
			if ( empty( $_COOKIE[$cookie_name] ) ) {
				return false;
			}
			$cookie = $_COOKIE[$cookie_name];
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
	 * @param  boolean  $remember  Remember logged in.
	 * @param  string   $secure    Secure cookie.
	 */
	function set_auth_cookie( $remember = false, $secure = '') {
		if ( $remember ) {
			$expiration = $expire = time() + apply_filters( 'password_protected_auth_cookie_expiration', 1209600, $remember );
		} else {
			$expiration = time() + apply_filters( 'password_protected_auth_cookie_expiration', 172800, $remember );
			$expire = 0;
		}

		if ( '' === $secure ) {
			$secure = is_ssl();
		}

		$secure_password_protected_cookie = apply_filters( 'password_protected_secure_password_protected_cookie', false, $secure );
		$password_protected_cookie = $this->generate_auth_cookie( $expiration, 'password_protected' );

		setcookie( $this->cookie_name(), $password_protected_cookie, $expire, COOKIEPATH, COOKIE_DOMAIN, $secure_password_protected_cookie, true );
		if ( COOKIEPATH != SITECOOKIEPATH ) {
			setcookie( $this->cookie_name(), $password_protected_cookie, $expire, SITECOOKIEPATH, COOKIE_DOMAIN, $secure_password_protected_cookie, true );
		}
	}

	/**
	 * Clear Auth Cookie
	 */
	function clear_auth_cookie() {
		setcookie( $this->cookie_name(), ' ', time() - 31536000, COOKIEPATH, COOKIE_DOMAIN );
		setcookie( $this->cookie_name(), ' ', time() - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN );
	}

	/**
	 * Cookie Name
	 *
	 * @return  string  Cookie name.
	 */
	function cookie_name() {
		return $this->get_site_id() . '_password_protected_auth';
	}

	/**
	 * Install
	 */
	function install() {
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
	 * Safe Redirect
	 *
	 * Ensure the redirect is to the same site or pluggable list of allowed domains.
	 * If invalid will redirect to ...
	 * Based on the WordPress wp_safe_redirect() function.
	 */
	function safe_redirect( $location, $status = 302 ) {
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

		// WP Engine
		if ( class_exists( 'WPE_API', false ) ) {
			return new WP_Error( 'PASSWORD_PROTECTED_SUPPORT', __( 'The Password Protected plugin does not work with WP Engine hosting. Please disable it.', 'password-protected' ) );
		}

		return true;
	}

}
