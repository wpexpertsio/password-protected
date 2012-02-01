<?php

/*
Plugin Name: Password Protected
Plugin URI: http://www.benhuson.co.uk/
A very simple way to quickly password protect your WordPress site with a single password. Integrates seamlessly into your WordPress privacy settings.
Version: 1.0
Author: Ben Huson
Author URI: http://www.benhuson.co.uk/
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

// Start session if not already started...
if ( session_id() == '' )
	session_start();

// Setup Password Protected
global $Password_Protected;
$Password_Protected = new Password_Protected();

class Password_Protected {
	
	var $admin = null;
	var $errors = null;
	
	/**
	 * Constructor
	 */
	function Password_Protected() {
		$this->errors = new WP_Error();
		add_action( 'init', array( $this, 'maybe_process_login' ), 1 );
		add_action( 'template_redirect', array( $this, 'maybe_show_login' ), 1 );
		$this->disable_feeds();
		if ( is_admin() ) {
			include_once( dirname( __FILE__ ) . '/admin/admin.php' );
			$this->admin = new Password_Protected_Admin();
		}
	}
	
	/**
	 * Is Active?
	 */
	function is_active() {
		if ( (bool) get_option( 'password_protected_status' ) )
			return true;
		return false;
	}
	
	/**
	 * Disable Feeds
	 */
	function disable_feeds() {
		add_action( 'do_feed', array( $this, 'disable_feed' ), 1 );
		add_action( 'do_feed_rdf', array( $this, 'disable_feed' ), 1 );
		add_action( 'do_feed_rss', array( $this, 'disable_feed' ), 1 );
		add_action( 'do_feed_rss2', array( $this, 'disable_feed' ), 1 );
		add_action( 'do_feed_atom', array( $this, 'disable_feed' ), 1 );
	}
	
	/**
	 * Disable Feed
	 * @todo Make Translatable
	 */
	function disable_feed() {
		wp_die( __( 'Feeds are not available for this site. Please visit the <a href="'. get_bloginfo( 'url' ) .'">website</a>.' ) );
	}
	
	/**
	 * Maybe Process Login
	 */
	function maybe_process_login() {
		if ( $this->is_active() && isset( $_REQUEST['password_protected_pwd'] ) ) {
			$password_protected_pwd = $_REQUEST['password_protected_pwd'];
			$pwd = get_option( 'password_protected_password' );
			// If correct password...
			if ( $password_protected_pwd == $pwd && $pwd != '' ) {
				$_SESSION[$this->get_site_id() . '_password_protected_auth'] = 1;
			} else {
				// ... otherwise incorrect password
				$this->errors->add( 'incorrect_password', 'Incorrect Password' );
			}
		}
		
		// Log out
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'logout' ) {
			$_SESSION[$this->get_site_id() . '_password_protected_auth'] = 0;
			$this->errors = new WP_Error();
			$this->errors->add( 'logged_out', __( 'You are now logged out.' ), 'message' );
		}
	}
	
	/**
	 * Maybe Show Login
	 */
	function maybe_show_login() {
		// Don't show login if not enabled
		if ( ! $this->is_active() )
			return;
		
		// Logged in
		if ( isset( $_SESSION[$this->get_site_id() . '_password_protected_auth'] ) && $_SESSION[$this->get_site_id() . '_password_protected_auth'] == 1 )
			return;
		
		// Show login form
		include( dirname( __FILE__ ) . '/theme/login.php' );
		exit();
	}
	
	/**
	 * Get Site ID
	 */
	function get_site_id() {
		global $blog_id;
		return apply_filters( 'password_protected_blog_id', $blog_id );
	}

}

?>