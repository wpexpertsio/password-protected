<?php
/**
 * Class Password Protected Activity Report Settings
 *
 * @package Password Protected Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Password_protected_Activity_Report_Settings' ) ) {
	class Password_protected_Activity_Report_Settings {
		private static $instance;

		private function __construct() {
			add_action( 'admin_init', array( $this, 'update_database' ), -1 );
			add_action( 'admin_init', array( $this, 'settings_fields' ), 10 );
			add_action( 'password_protected_subtab_activity-report_content', array( $this, 'activity_report' ) );

			if ( self::is_activity_report_enabled() ) {
				if ( ! class_exists( 'Password_Protected_Pro' ) ) {
					require_once PASSWORD_PROTECTED_DIR . 'includes/activity-report-email/class-password-protected-activity-logs.php';

					add_action( 'password_protected_success_login_attempt', array( $this, 'success_attempt' ), 10, 3 );
					add_action( 'password_protected_failure_login_attempt', array( $this, 'failure_attempt' ), 10, 3 );
					add_action( 'password_protected_after_login_form', array( $this, 'login_enqueue_scripts' ) );
					add_action( 'password_protected_below_password_field', array( $this, 'add_new_field_after_password_field' ) );
				}

				require_once PASSWORD_PROTECTED_DIR . 'includes/activity-report-email/class-password-protected-send-email-notification.php';
			}
		}

		public function update_database() {
			$plugin_updated = get_option( 'password_protected_1.5_update_database', false );
			if ( ! $plugin_updated ) {
				global $wpdb;
				$table_name      = $wpdb->prefix . 'pp_activity_logs';
				$charset_collate = $wpdb->get_charset_collate();
				if ( ! function_exists( 'maybe_create_table' ) ) {
					require_once ABSPATH . 'wp-admin/includes/upgrade.php';
				}

				$sql = 'CREATE TABLE `' . $table_name . '` (
					`id`         MEDIUMINT ( 9 )  NOT NULL AUTO_INCREMENT,
					`ip`         VARCHAR   ( 55 ) NOT NULL,
					`browser`    TEXT             NOT NULL,
					`status`     TINYTEXT         NOT NULL,
					`created_at` VARCHAR   ( 55 ) NOT NULL,
					PRIMARY KEY ( `id` )
				) ' . $charset_collate . ';';

				maybe_create_table( $table_name, $sql );
				update_option( 'password_protected_1.5_update_database', true );
			}
		}

		public function settings_fields() {
			register_setting(
				'password_protected_activity_report',
				'password_protected_activity_report_enable',
				array(
					'sanitize_callback' => array( $this, 'sanitize_report_fields' ),
				)
			);

			add_settings_section(
				'password_protected_activity_report',
				__( 'Password Activity Report via Email', 'password-protected' ),
				'__return_null',
				'admin.php?page=password-protected&tab=activity-report',
				array()
			);

			add_settings_field(
				'password_protected_activity_report',
				__( 'Enable Activity Report', 'password-protected' ),
				array( $this, 'activity_report_field_callback' ),
				'admin.php?page=password-protected&tab=activity-report',
				'password_protected_activity_report',
				array(
					'label_for' => 'password_protected_activity_report',
				)
			);
		}

		public function sanitize_report_fields( $fields ) {
			if ( empty( $fields ) ) {
				return 'no';
			}

			return $fields;
		}

		public function activity_report_field_callback( $args ) {
			$checked = get_option( 'password_protected_activity_report_enable', 'no' );
			$checked = 'yes' === $checked ? 'checked' : '';
			echo '<div class="pp-toggle-wrapper">
				<input id="' . esc_attr( $args['label_for'] ) . '" value="yes" name="password_protected_activity_report_enable" type="checkbox" ' . $checked . ' />
				<label for="' . esc_attr( $args['label_for'] ) . '" class="pp-toggle">
					<span class="pp-toggle-slider"></span>
				</label>
			</div>
			<p class="description">
				<label for="' . esc_attr( $args['label_for'] ) . '">
					' . __( 'Enable this option to receive weekly activity report on your email.', 'password-protected' ) . '
				</label>
			</p>';
		}

		public function add_activity_report_tab( $tabs ) {
			$tabs['activity-report'] = __( 'Activity Report', 'password-protected' );
			return $tabs;
		}

		public function activity_report() {
			echo '<form action="options.php" method="post" enctype="multipart/form-data">';

				settings_fields( 'password_protected_activity_report' );
				do_settings_sections( 'admin.php?page=password-protected&tab=activity-report' );

				submit_button();

			echo '</form>';
		}

		public function success_attempt( $form_type, $password, $password_id ) {
			$this->log_password_attempt( "Success", $form_type, $password, $password_id );
		}

		public function failure_attempt( $form_type, $password, $password_id ) {
			$this->log_password_attempt( "Failure", $form_type, $password, $password_id );
		}

		public function login_enqueue_scripts() {
		global $Password_Protected;
		wp_enqueue_script( 'password-protected-detect', PASSWORD_PROTECTED_URL . 'assets/js/detect.min.js', array( 'jquery' ), $Password_Protected->version, true );
		wp_enqueue_script( 'password-protected-compatibility', PASSWORD_PROTECTED_URL . 'assets/js/compatibility.js', array( 'password-protected-detect' ), $Password_Protected->version, true );
	}

		public function add_new_field_after_password_field() {
			echo '<input type="hidden" name="password_protected_user_agent" value="" />';
		}

		private function log_password_attempt( $success_or_failure, $form_type, $password, $password_id ) {
			$log = $this->prepare_entry_log();
			extract( $log );
			Password_Protected_Activity_Logs::add_item(
				array(
					'ip'            => $IP,
					'browser'       => $browser,
					'status'        => $success_or_failure,
					'created_at'    => current_time( "timestamp" ),
					'password_id'   => $password_id,
					'object_type'   => $form_type,
					'used_password' => $password,
				)
			);
		}

		private function prepare_entry_log() {
			$IP      = self::get_client_ip();
			$browser = self::get_browser();
			return compact( 'IP', 'browser' );
		}

		public static function get_client_ip() {
			$ipaddress = 'UNKNOWN';
			$keys      = array(
				'HTTP_CLIENT_IP',
				'HTTP_X_FORWARDED_FOR',
				'HTTP_X_FORWARDED',
				'HTTP_FORWARDED_FOR',
				'HTTP_FORWARDED',
				'REMOTE_ADDR',
			);

			foreach ( $keys as $key ) {
				if ( isset( $_SERVER[ $key ] ) ) {
					$ipaddress = sanitize_text_field( wp_unslash( $_SERVER[ $key ] ) );
					break;
				}
			}

			if ( '::1' === $ipaddress ) {
				$ipaddress = '127.0.1.6';
			}

			return $ipaddress;
		}

		public static function get_browser() {
			if ( isset( $_POST['password_protected_user_agent'] ) ) {
				return sanitize_text_field( wp_unslash( $_POST['password_protected_user_agent'] ) );
			}

			return 'UNKNOWN';
		}

		public static function is_activity_report_enabled() {
			return 'yes' === get_option( 'password_protected_activity_report_enable', 'no' );
		}

		public static function get_report_interval() {
			$interval = apply_filters( 'password_protected_activity_report_interval', 7 );
			if ( ! $interval ) {
				$interval = 7;
			}

			$day_into_seconds = 60*60*24;
			return $day_into_seconds * $interval;
		}

		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}

Password_protected_Activity_Report_Settings::get_instance();
