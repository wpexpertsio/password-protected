<?php

/**
 * @package     Password Protected
 * @subpackage  Admin Bar
 *
 * Adds an indicator in the admin if Password Protection is enabled.
 */

namespace Password_Protected;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'plugins_loaded', array( 'Password_Protected\Admin_Bar', 'load' ), 15 );

class Admin_Bar {

	/**
	 * Load
	 *
	 * @internal  Private. Called via `plugins_loaded` actions.
	 */
	public static function load() {

		add_action( 'wp_head', array( get_class(), 'styles' ) );
		add_action( 'admin_head', array( get_class(), 'styles' ) );
		add_action( 'wp_before_admin_bar_render', array( get_class(), 'toolbar_item' ) );

	}

	/**
	 * Toolbar Item
	 *
	 * @internal  Private. Called via `wp_before_admin_bar_render` actions.
	 */
	public static function toolbar_item() {

		global $wp_admin_bar;

		if ( self::allow_current_user() ) {

			if ( (bool) get_option( 'password_protected_status' ) ) {
				$title = __( 'Password Protection is enabled.', 'password-protected' );
			} else {
				$title = __( 'Password Protection is disabled.', 'password-protected' );
			}

			$wp_admin_bar->add_menu( array(
				'id'     => 'password_protected',
				'title'  => __( '', 'password-protected' ),
				'href'   => admin_url( 'options-general.php?page=password-protected' ),
				'meta'   => array(
					'title' => $title
				)
			) );

		}

	}

	/**
	 * Styles
	 *
	 * @internal  Private. Called via `wp_head` and `admin_head` actions.
	 */
	public static function styles() {

		if ( self::allow_current_user() ) {

			if ( (bool) get_option( 'password_protected_status' ) ) {
				$icon = '\f160';
				$background = '#C00';
			} else {
				$icon = '\f528';
				$background = 'transparent';
			}

			?>
			<style type="text/css">
			#wp-admin-bar-password_protected { background-color: <?php echo $background; ?> !important; }
			#wp-admin-bar-password_protected > .ab-item { color: #fff !important;  }
			#wp-admin-bar-password_protected > .ab-item:before { content: "<?php echo $icon; ?>"; top: 2px; color: #fff !important; margin-right: 0px; }
			#wp-admin-bar-password_protected:hover > .ab-item { background-color: <?php echo $background; ?> !important; color: #fff; }
			</style>
			<?php

		}

	}

	/**
	 * Allow Current User
	 *
	 * @return  boolean
	 */
	private static function allow_current_user() {

		return is_user_logged_in();

	}

}
