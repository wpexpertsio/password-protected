<?php
/**
 * Freemius integration
 *
 * @package Password Protected Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'pp_free_fs' ) ) {
	/**
	 * Freemius integration
	 *
	 * @return Freemius Freemius instance.
	 * @throws Freemius_Exception Throws Freemius exception.
	 */
	function pp_free_fs() {
		global $pp_free_fs;

		if ( ! isset( $pp_free_fs ) ) {
			require_once dirname( __DIR__  ) . '/freemius/start.php';

			$pp_free_fs = fs_dynamic_init(
				array(
					'id'                  => '12503',
					'slug'                => 'password-protected-free',
					'premium_slug'        => 'password-protected-premium-premium',
					'type'                => 'plugin',
					'public_key'          => 'pk_e9210517721d27b5112fa7773a600',
					'is_premium'          => false,
//					'has_addons'          => true,
					'has_paid_plans'      => false,
					'menu'                => array(
						'slug'           => 'password-protected',
						'support'        => false,
						'contact'        => false,
					),
				)
			);
		}

		return $pp_free_fs;
	}
}

pp_free_fs();