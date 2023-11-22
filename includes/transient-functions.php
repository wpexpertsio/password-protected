<?php
/**
 * Transient functions.
 *
 * @usage Transient functions to get, set and delete transient values
 * @description Use transient instead of cookies.
 * @package Password Protected
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'pp_get_transient_identifier' ) ) {
	/**
	 * Get identifier
	 *
	 * @param string $key transient key.
	 *
	 * @usage function to get unique transient key
	 * @return string: transient key
	 */
	function pp_get_transient_identifier( $key = '' ) {
		return $key . '_' . pp_get_ip_address();
	}
}

if ( ! function_exists( 'pp_get_ip_address' ) ) {
	/**
	 * Get IP Address
	 *
	 * @usage function to get ip of current user
	 * @return string: ip
	 */
	function pp_get_ip_address() {
		foreach ( array( 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR' ) as $key ) {
			if ( array_key_exists( $key, $_SERVER ) === true ) {
				foreach ( explode(',', $_SERVER[ $key ] ) as $ip ) {
					$ip = trim( $ip );

					if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== false ){
						return $ip;
					}
				}
			}
		}
	}
}

if ( ! function_exists( 'pp_get_transient' ) ) {
	/**
	 * Get Transient
	 *
	 * @usage function to get transient value by key
	 *
	 * @param string $key transient key.
	 *
	 * @return bool|mixed
	 */
	function pp_get_transient( $key = '' ) {
		$transient = pp_get_transient_identifier( $key );
		$value = get_transient( $transient );
		if ( ! empty( $value ) ) {
			return $value;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'pp_set_transient' ) ) {
	/**
	 * Set Transient
	 *
	 * @param string $key transient key.
	 * @param string $value transient value.
	 * @param int    $duration transient duration.
	 *
	 * @usage function to set transient value by key, value and duration
	 */
	function pp_set_transient( $key = '', $value = '', $duration = HOUR_IN_SECONDS ) {
		$transient = pp_get_transient_identifier( $key );
		set_transient( $transient, $value, $duration );
	}
}

if ( ! function_exists( 'pp_delete_transient' ) ) {
	/**
	 * Delete transient
	 *
	 * @param string $key transient key.
	 *
	 * @usage function to delete transient value by key
	 */
	function pp_delete_transient( $key = '' ) {
		$transient = pp_get_transient_identifier( $key );
		delete_transient( $transient );
	}
}
