<?php

/**
 * @package     Password Protected
 * @subpackage  Debugger
 */

namespace Password_Protected;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Debugger {

	protected static $log = array();

	public static function add( $string ) {

		if ( self::is_enabled() ) {
			self::$log[] = $string;
		}

	}

	public static function save() {

		if ( self::is_enabled() ) {

			$append = implode( PHP_EOL, self::$log );

			self::append_to_log( $append );

			self::$log = array();

		}

	}

	public static function get_log() {

		return get_option( 'password_protected_debug_log' );

	}

	public static function append_to_log( $append ) {

		if ( ! empty( $append ) ) {

			$log = self::get_log();
			self::replace_log( $log . $append . PHP_EOL );

		}

	}

	public static function replace_log( $log ) {

		update_option( 'password_protected_debug_log', wp_kses( $log, array() ), false );

	}

	public static function clear_log() {

		update_option( 'password_protected_debug_log', '', true );

	}

	protected static function is_enabled() {

		return 1 == get_option( 'password_protected_debugging' );

	}

}
