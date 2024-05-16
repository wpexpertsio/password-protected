<?php


defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Password_Protected_Send_Email_Notification' ) ) {
	class Password_Protected_Send_Email_Notification {
		private static $instance;

		private function __construct() {
			add_filter( 'cron_schedules', array( $this, 'cron_schedules' ) );
			add_action( 'init', array( $this, 'init_cron' ) );
			add_action( 'password_protected_email_notification_hook', array( $this, 'send_email_notification' ) );
		}

		public function cron_schedules( $schedules ) {
			$schedules['password_protected_email_notification'] = array(
				'interval' => Password_protected_Activity_Report_Settings::get_report_interval(),
				'display'  => __( 'Password Protected Email Notification Interval', 'password-protected' ),
			);
			return $schedules;
		}

		public function init_cron() {
			if ( ! wp_next_scheduled( 'password_protected_email_notification_hook' ) ) {
				wp_schedule_event( time(), 'password_protected_email_notification', 'password_protected_email_notification_hook' );
			}
		}

		public function send_email_notification() {
			global $wpdb;
			$timestamps = Password_Protected_Activity_Logs::get_time_from_keyword( 'thisweek' );
			$sql        = 'SELECT
			    SUM( IF ( `status` = %s, 1, 0 ) ) as success,
			    SUM( IF ( `status` = %s, 1, 0 ) ) as failed
			FROM %i WHERE created_at between %d and %d;';
			$sql        = $wpdb->prepare( $sql, 'Success', 'Failure', $wpdb->prefix . 'pp_activity_logs', $timestamps[0], $timestamps[1] );
			$results    = $wpdb->get_row( $sql, ARRAY_A );

			$success_attempts =
			$failed_attempts  = 0;
			if ( is_array( $results ) ) {
				if ( isset( $results['success'] ) ) {
					$success_attempts = absint( $results['success'] );
				}

				if ( isset( $results['failed'] ) ) {
					$failed_attempts = absint( $results['failed'] );
				}
			}

			$total_attempts   = $success_attempts + $failed_attempts;

			$template = $this->get_template( $success_attempts, $failed_attempts, $total_attempts, $timestamps );
			$headers  = $this->get_html_headers();
			$subject  = sprintf(
				'[ %s, %s ]',
				get_bloginfo( 'name' ),
				__( 'Password Protected Activity Log Notification', 'password-protected' )
			);

			return wp_mail( get_option( 'admin_email' ), $subject, $template, $headers );
		}

		private function get_template( $success, $failed, $total, $time ) {
			ob_start();
			require_once PASSWORD_PROTECTED_DIR . 'templates/emails/activity-notification.php';
			return ob_get_clean();
		}
		private function get_html_headers() {
			$headers = array(
				'Content-type: text/html',
			);

			return implode( "\r\n", apply_filters( 'password_protected_email_headers', $headers ) );
		}

		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}

Password_Protected_Send_Email_Notification::get_instance();

#F03B3E, #04AA5E, #4685EC, #CA1329, #FBBA40