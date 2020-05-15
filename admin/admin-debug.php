<?php

/**
 * @package     Password Protected
 * @subpackage  Debugging
 *
 * @since  2.2.6
 */

class Password_Protected_Admin_Debug {

	/**
	 * Plugin
	 *
	 * @since  2.2.6
	 *
	 * @var  Password_Protected|null
	 */
	private $plugin = null;

	/**
	 * Constructor
	 *
	 * @since  2.2.6
	 *
	 * @internal  Private. This class should only be instantiated once by the plugin.
	 */
	public function __construct( $plugin ) {

		$this->plugin = $plugin;

		if ( 1 == get_option( 'password_protected_debugging' ) ) {
			add_action( 'admin_init', array( $this, 'debug_settings_info' ) );
		}

	}

	/**
	 * Debug Settings Info
	 *
	 * Displays information on the settings page for helping
	 * to debug Password Protected issues.
	 *
	 * @since  2.2.6
	 */
	public function debug_settings_info() {

		// Caching Section
		add_settings_section(
			'password_protected_debug',
			__( 'Debugging', 'password-protected' ),
			array( $this, 'section_debugging' ),
			'password-protected-compat'
		);

	}

	/**
	 * Debugging Section
	 *
	 * @since  2.2.6
	 */
	public function section_debugging() {

		$log = get_option( 'password_protected_debug_log' );

		echo '<p>' . __( 'This panel will try to log what happens when a user enters the password on the frontend of the website.', 'password-protected' ) . '<br />
			' . __( 'Logged information will be erased when you disable debugging.', 'password-protected' ) . '</p>
			<p><textarea rows="8" style="width: 100%; font-size: 10px;" placeholder="' . esc_attr__( 'No information logged yet. Please try entering the password on the frontend of the website and check back here for information.', 'password-protected' ) . '">' . esc_html( $log ) . '</textarea></p>';

	}

}
