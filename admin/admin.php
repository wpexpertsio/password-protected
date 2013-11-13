<?php

class Password_Protected_Admin {
	
	var $settings_page_id;
	var $options_group = 'password-protected';
	
	/**
	 * Constructor
	 */
	function Password_Protected_Admin() {
		global $wp_version;
		add_action( 'admin_init', array( $this, 'password_protected_settings' ), 5 );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'password_protected_help_tabs', array( $this, 'help_tabs' ), 5 );
		add_action( 'admin_notices', array( $this, 'password_protected_admin_notices' ) );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );
		add_filter( 'plugin_action_links_password-protected/password-protected.php', array( $this, 'plugin_action_links' ) );
		add_filter( 'pre_update_option_password_protected_password', array( $this, 'pre_update_option_password_protected_password' ), 10, 2 );
		add_filter( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Admin Menu
	 */
	function admin_menu() {
		$this->settings_page_id = add_options_page( __( 'Password Protected', 'password-protected' ), __( 'Password Protected', 'password-protected' ), 'manage_options', 'password-protected', array( $this, 'settings_page' ) );
		add_action( 'load-' . $this->settings_page_id, array( $this, 'add_help_tabs' ), 20 );
	}

	/**
	 * Settings Page
	 */
	function settings_page() {
		echo '<div class="wrap">
			<div id="icon-options-general" class="icon32"><br /></div>
			<h2>' . __( 'Password Protected Settings', 'password-protected' ) . '</h2>
			<form method="post" action="options.php">';
		settings_fields( 'password-protected' );
		do_settings_sections( 'password-protected' );
		echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="' . __( 'Save Changes' ) . '"></p>
			</form>
			</div>';
	}

	/**
	 * Add Help Tabs
	 */
	function add_help_tabs() {
		global $wp_version;
		if ( version_compare( $wp_version, '3.3', '<' ) )
			return;
		do_action( 'password_protected_help_tabs', get_current_screen() );
	}

	/**
	 * Help Tabs
	 */
	function help_tabs( $current_screen ) {
		$current_screen->add_help_tab( array(
			'id'      => 'PASSWORD_PROTECTED_SETTINGS',
			'title'   => __( 'Password Protected', 'password-protected' ),
			'content' => __( '<p><strong>Enabled Checkbox</strong><br />Turn on/off password protection.</p>', 'password-protected' )
				. __( '<p><strong>Allow RSS Feeds Checkbox</strong><br />RSS Feeds will be able to accessed even when the site is password proteced.</p>', 'password-protected' )
				. __( '<p><strong>Allow Administrators Checkbox</strong><br />Administrators will not need to enter a password to view the site (providing they are logged in of course). You will also need to enable this option if you want administrators to be able to preview the site in the Theme Customizer.</p>', 'password-protected' )
				. __( '<p><strong>Password Fields</strong><br />To set a new password, enter it into both fields. You cannot set an `empty` password. To disable password protection uncheck the Enabled checkbox.</p>', 'password-protected' )
		) );
	}
	
	/**
	 * Admin Enqueue Scripts
	 */
	function admin_enqueue_scripts() {
		$current_screen = get_current_screen();
		if ( 'settings_page_' . $this->options_group == $current_screen->id ) {
			wp_enqueue_script( 'password_protected_settings', PASSWORD_PROTECTED_URL . '/admin/js/settings.js', array( 'jquery' ) );
		}
	}
	
	/**
	 * Settings API
	 */
	function password_protected_settings() {
		add_settings_section(
			'password_protected',
			'',
			array( $this, 'password_protected_settings_section' ),
			$this->options_group
		);
		add_settings_field(
			'password_protected_status',
			__( 'Password Protected Status', 'password-protected' ),
			array( $this, 'password_protected_status_field' ),
			$this->options_group,
			'password_protected'
		);
		add_settings_field(
			'password_protected_password',
			__( 'New Password', 'password-protected' ),
			array( $this, 'password_protected_password_field' ),
			$this->options_group,
			'password_protected'
		);
 		register_setting( $this->options_group, 'password_protected_status', 'intval' );
 		register_setting( $this->options_group, 'password_protected_feeds', 'intval' );
 		register_setting( $this->options_group, 'password_protected_administrators', 'intval' );
		register_setting( $this->options_group, 'password_protected_password', array( $this, 'sanitize_password_protected_password' ) );
 	}
	
	/**
	 * Sanitize Password Field Input
	 */
	function sanitize_password_protected_password( $val ) {
		$old_val = get_option( 'password_protected_password' );
		if ( is_array( $val ) ) {
			if ( empty( $val['new'] ) ) {
				return $old_val;
			} elseif ( empty( $val['confirm'] ) ) {
				add_settings_error( 'password_protected_password', 'password_protected_password', __( 'New password not saved. When setting a new password please enter it in both fields.', 'password-protected' ) );
				return $old_val;
			} elseif ( $val['new'] != $val['confirm'] ) {
				add_settings_error( 'password_protected_password', 'password_protected_password', __( 'New password not saved. Password fields did not match.', 'password-protected' ) );
				return $old_val;
			} elseif ( $val['new'] == $val['confirm'] ) {
				add_settings_error( 'password_protected_password', 'password_protected_password', __( 'New password saved.', 'password-protected' ), 'updated' );
				return $val['new'];
			}
			return get_option( 'password_protected_password' );
		}
		return $val;
	}
	
	/**
	 * Password Protected Section
	 */
	function password_protected_settings_section() {
		echo '<p>' . __( 'Password protect your web site. Users will be asked to enter a password to view the site.', 'password-protected' ) . '<br />
			' . __( 'For more information about Password Protected settings, view the "Help" tab at the top of this page.', 'password-protected' ) . '</p>';
	}
	
	/**
	 * Password Protection Status Field
	 */
	function password_protected_status_field() {
		echo '<input name="password_protected_status" id="password_protected_status" type="checkbox" value="1" ' . checked( 1, get_option( 'password_protected_status' ), false ) . ' /> ' . __( 'Enabled', 'password-protected' );
		echo '<input name="password_protected_feeds" id="password_protected_feeds" type="checkbox" value="1" ' . checked( 1, get_option( 'password_protected_feeds' ), false ) . ' style="margin-left: 20px;" /> ' . __( 'Allow RSS Feeds', 'password-protected' );
		echo '<input name="password_protected_administrators" id="password_protected_administrators" type="checkbox" value="1" ' . checked( 1, get_option( 'password_protected_administrators' ), false ) . ' style="margin-left: 20px;" /> ' . __( 'Allow Administrators', 'password-protected' );
	}
	
	/**
	 * Password Field
	 */
	function password_protected_password_field() {
		echo '<input type="password" name="password_protected_password[new]" id="password_protected_password_new" size="16" value="" autocomplete="off"> <span class="description">' . __( 'If you would like to change the password type a new one. Otherwise leave this blank.', 'password-protected' ) . '</span><br>
			<input type="password" name="password_protected_password[confirm]" id="password_protected_password_confirm" size="16" value="" autocomplete="off"> <span class="description">' . __( 'Type your new password again.', 'password-protected' ) . '</span>';
	}
	
	/**
	 * Pre-update 'password_protected_password' Option
	 *
	 * Before the password is saved, MD5 it!
	 * Doing it in this way allows developers to intercept with an earlier filter if they
	 * need to do something with the plaintext password.
	 *
	 * @param string $newvalue New Value.
	 * @param string $oldvalue Old Value.
	 * @return string Filtered new value.
	 */
	function pre_update_option_password_protected_password( $newvalue, $oldvalue ) {
		global $Password_Protected;
		if ( $newvalue != $oldvalue ) {
			$newvalue = $Password_Protected->encrypt_password( $newvalue );
		}
		return $newvalue;
	}

	/**
	 * Plugin Row Meta
	 *
	 * Adds GitHub link below the plugin description on the plugins page.
	 *
	 * @param   array   $plugin_meta  Plugin meta display array.
	 * @param   string  $plugin_file  Plugin reference.
	 * @param   array   $plugin_data  Plugin data.
	 * @param   string  $status       Plugin status.
	 * @return  array                 Plugin meta array.
	 */
	function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
		if ( 'password-protected/password-protected.php' == $plugin_file ) {
			$plugin_meta[] = sprintf( '<a href="%s">%s</a>', __( 'http://github.com/benhuson/password-protected', 'password-protected' ), __( 'GitHub', 'password-protected' ) );
		}
		return $plugin_meta;
	}

	/**
	 * Plugin Action Links
	 *
	 * Adds settings link on the plugins page.
	 *
	 * @param   array  $actions  Plugin action links array.
	 * @return  array            Plugin action links array.
	 */
	function plugin_action_links( $actions ) {
		$actions[] = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=password-protected' ), __( 'Settings', 'password-protected' ) );
		return $actions;
	}

	/**
	 * Password Admin Notice
	 * Warns the user if they have enabled password protection but not entered a password
	 */
	function password_protected_admin_notices(){
		$current_screen = get_current_screen();
		if ( $current_screen->id == 'options-' . $this->options_group ) {
			$status = get_option( 'password_protected_status' );
			$pwd = get_option( 'password_protected_password' );
			if ( (bool) $status && empty( $pwd ) ) {
				echo '<div class="error"><p>' . __( 'You have enabled password protection but not yet set a password. Please set one below.', 'password-protected' ) . '</p></div>';
			}
			if ( current_user_can( 'manage_options' ) && (bool) get_option( 'password_protected_administrators' ) ) {
				echo '<div class="error"><p>' . __( 'You have enabled password protection and allowed administrators - other users will still need to login to view the site.', 'password-protected' ) . '</p></div>';
			}
		}
	}

}

?>