<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Password_Protected_Activity_Logs' ) ) {
	class Password_Protected_Activity_Logs {
		/**
		 * table
		 *
		 * @var mixed
		 */
		private static $table;
		/**
		 * now
		 *
		 * @var int
		 */
		public static $now = 0;
		/**
		 * Method __construct
		 */
		public function __construct() {
			self::$now          = current_time( 'timestamp' );
			self::$table        = 'pp_activity_logs';
			$database_updated = get_option( 'pp_activity_logs_db_updated' );
			if ( ! $database_updated ) {
				if ( ! function_exists( 'maybe_add_column' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				}
				global $wpdb;
				$table_name = $wpdb->prefix . self::$table;
				$used_password_col = maybe_add_column(
					$table_name,
					'used_password',
					'ALTER TABLE ' . $table_name . ' ADD `used_password` VARCHAR ( 255 ) NULL AFTER `status`'
				);
				$password_id_col   = maybe_add_column(
					$table_name,
					'password_id',
					'ALTER TABLE ' . $table_name . ' ADD `password_id` VARCHAR( 255 ) NULL AFTER `id`'
				);
				$object_type_col   = maybe_add_column(
					$table_name,
					'object_type',
					'ALTER TABLE ' . $table_name . ' ADD `object_type` VARCHAR( 255 ) NULL AFTER `status`'
				);

				if ( $used_password_col && $password_id_col && $object_type_col ) {
					update_option( 'pp_activity_logs_db_updated', true );
				}
			}
		}

		/**
		 * Method get_items
		 *
		 * @return array|object|stdClass[]|null
		 */
		public static function get_items() {

			global $wpdb;
			$table_name     = $wpdb->prefix . self::$table;

			$search_term    = self::check_for_search();
			$filter         = self::check_for_filter();
			$timestamp       = self::get_time_from_keyword( $filter );


			if( $filter != NULL && !empty( $timestamp ) )
				$query = " WHERE `created_at` BETWEEN " . reset( $timestamp ) . " AND " . end( $timestamp );
			else
				$query = "";

			if( $search_term == NULL ) {
				$search = $query . " ORDER BY `id` DESC";
			} else {
				$query = empty($query) ? '' : str_replace( "WHERE", "and", $query );
				$search = "WHERE CONCAT_WS( ' ', `ip`,  `browser`, `status` ) LIKE '%$search_term%'" . $query;
			}

			$logs = $wpdb->get_results(
				" SELECT * FROM  $table_name " . $search,
				ARRAY_A
			);

			if( !is_wp_error( $logs ) && count( $logs ) > 0 )
				return $logs;
			else
				return array();
		}

		/**
		 * Method add_item
		 *
		 * @param $request $request
		 *
		 * @return bool|int|mysqli_result|null
		 */
		public static function add_item( $request ) {
			global $wpdb;
			$table_name = $wpdb->prefix . self::$table;

			$data = array(
				'ip'            => $request['ip'],
				'browser'       => $request['browser'],
				'status'        => $request['status'],
				'created_at'    => $request['created_at'],
				'used_password' => $request['used_password'],
				'password_id'   => $request['password_id'],
				'object_type'   => $request['object_type'],
			);
			$format = array( '%s', '%s', '%s', '%s' );
			return $wpdb->insert( $table_name, $data, $format );
		}

		/**
		 * delete_item
		 *
		 * @param  mixed $id
		 *
		 * @return bool|int|mysqli_result|null
		 */
		public static function delete_item( $id ) {
			global $wpdb;

			return $wpdb->delete(
				$wpdb->prefix . self::$table,
				['id' => $id],
				['%d']
			);
		}

		/**
		 * delete_items
		 *
		 * @param  mixed $ids
		 */
		public static function delete_items( $ids ) {
			global $wpdb;

			$table_name = $wpdb->prefix . self::$table;
			$wpdb->query( "DELETE FROM `{$table_name}` WHERE ID IN( $ids )" );
		}

		/**
		 * delete_all_items
		 */
		public static function delete_all_items() {
			global $wpdb;
			$table_name = $wpdb->prefix . self::$table;
			$wpdb->query("TRUNCATE TABLE $table_name");
		}

		/**
		 * check_for_search
		 *
		 * @return string|null
		 */
		public static function check_for_search() {
			if( isset( $_POST['s'] ) ) {
				if ( ! isset( $_POST['search_activity_logs_nonce'] )  || ! wp_verify_nonce( $_POST['search_activity_logs_nonce'], 'password_protected_search_activity_logs' )  ) {
					wp_die('Sorry, your nonce did not verify.');
				}
				return trim( sanitize_text_field( $_POST['s'] )  );

			} else {
				return null;
			}
		}

		/**
		 * check_for_filter
		 *
		 * @return string|null
		 */
		public static function check_for_filter() {
			if( isset( $_GET['show_logs'] ) ) {

				$nonce = sanitize_text_field( $_GET['_wpnonce'] );
				if( ! wp_verify_nonce( $nonce, 'activity-logs-filter' ) ) {
					wp_die( __( 'Security check: Your nonce did not verify!', 'password-protected-pro' ) );
				} else {
					return sanitize_text_field( $_GET['show_logs'] );
				}
			}
			return null;
		}

		/**
		 * get_time_from_keyword
		 *
		 * @param  mixed $keyword
		 *
		 * @return array
		 */
		public static function get_time_from_keyword( $keyword = '' ) {

			$today                 = strtotime( date( 'Y-m-d' ) . ' midnight', self::$now );
			$todays_date           = date( 'd', self::$now );
			$weekday               = date( 'w', self::$now );
			$result                = array();

			$keyword               = strtolower( (string) $keyword );

			// Today
			if ( $keyword === 'today' ) {
				$result[] = $today;
				$result[] = self::$now;
			}

			// Yesterday
			elseif ( $keyword === 'yesterday' ) {
				$result[] = strtotime( '-1 day midnight', self::$now );
				$result[] = strtotime( 'today midnight', self::$now );
			}

			// This week
			elseif ( $keyword === 'thisweek' ) {

				$thisweek  = strtotime( '-' . ( $weekday+1 ) . ' days midnight', self::$now );
				if ( get_option( 'start_of_week' ) == $weekday )
					$thisweek = $today;

				$result[] = $thisweek;
				$result[] = self::$now;

			}

			// This month
			elseif ( $keyword === 'thismonth' ) {
				$result[] = strtotime( date( 'Y-m-01' ) . ' midnight', self::$now );
				$result[] = self::$now;
			}

			return $result;

		}
	}
}

new Password_Protected_Activity_Logs();