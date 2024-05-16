<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * @package     Password Protected
 * @subpackage  reCAPTCHA
 *
 * @since  2.6
 */

class Password_Protected_reCAPTCHA {

	public $options_group = 'password-protected-recaptcha';
	public $options_name  = 'password_protected_recaptcha';
	public $tab           = 'password-protected&tab=advanced';
	public $settings      = array();
	/**
	 * Constructor
	 *
	 * @since  2.6
	 *
	 * @internal  public. This class should only be instantiated once by the plugin.
	 */
	public function __construct() {

		$options = get_option( $this->options_name );

		if ( empty( $options ) || ! $options ) {
			$this->settings = $this->get_default_reCAPTCHA_settings();
		} else {
			$this->settings = get_option( $this->options_name );
		}

		add_action( 'admin_init', array( $this, 'register_reCAPTCHA_settings' ), 6 );
        add_action( 'password_protected_subtab_google-recaptcha_content', array( $this, 'google_recaptcha_settings' ) );

		add_action( 'password_protected_after_password_field', array( $this, 'add_recaptcha' ) );

		add_filter( 'password_protected_verify_recaptcha', array( $this, 'verify_recaptcha' ) );
	}

	/**
	 * reCAPTCHA Default Settings
	 *
	 * @return array
	 * @since  2.6
	 */
	private function get_default_reCAPTCHA_settings(): array {
		return array(
			'enable'        => 0,
			'version'       => 'google_recaptcha_v2',
			'v2_site_key'   => null,
			'v3_site_key'   => null,
			'v2_secret_key' => null,
			'v3_secret_key' => null,
			'v3_score'      => 0.3,
			'v3_badge'      => 'bottomright',
			'v2_theme'      => 'light',
		);
	}

    public function google_recaptcha_settings() {
        ?>
        <div class="wrap">
            <h1><?php _e( 'Google reCAPTCHA Settings', 'password-protected' ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'password-protected-advanced' );
                do_settings_sections( 'password-protected&tab=advanced' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

	/**
	 * reCAPTCHA  Settings Info
	 *
	 * Displays information on the settings page for helping
	 * to configure Password Protected to work with Google reCAPTCHA v2 and v3.
	 *
	 * @since  2.6
	 */
	public function register_reCAPTCHA_settings() {
		// reCAPTCHA Section
		add_settings_section(
			$this->options_group,
			__( 'Google reCAPTCHA', 'password-protected' ),
			array( $this, 'reCAPTCHA_section' ),
			$this->tab
		);

		// Enable reCAPTCHA
		add_settings_field(
			'password_protected_enable_recaptcha',
			__( 'Enable reCAPTCHA ', 'password-protected' ),
			array( $this, 'reCAPTCHA_enable' ),
			$this->tab,
			$this->options_group
		);

		// reCAPTCHA version v2/v3
		add_settings_field(
			'password_protected_recaptcha_settings',
			__( 'Captcha Settings', 'password-protected' ),
			array( $this, 'reCAPTCHA_setting' ),
			$this->tab,
			$this->options_group
		);

		// reCAPTCHA v2/v3 sitekey
		add_settings_field(
			'password_protected_recaptcha_v2_site_key',
			__( 'Site Key', 'password-protected' ),
			array( $this, 'reCAPTCHA_site_key' ),
			$this->tab,
			$this->options_group
		);

		// reCAPTCHA v2/v3 secretkey
		add_settings_field(
			'password_protected_recaptcha_v2_secret_key',
			__( 'Secret Key', 'password-protected' ),
			array( $this, 'reCAPTCHA_secret_key' ),
			$this->tab,
			$this->options_group
		);

		// reCAPTCHA v3 score
		add_settings_field(
			'password_protected_recaptcha_score',
			__( 'Score', 'password-protected' ),
			array( $this, 'reCAPTCHA_score' ),
			$this->tab,
			$this->options_group
		);

		// reCAPTCHA v3 badgeposition
		add_settings_field(
			'password_protected_recaptcha_badge_position',
			__( 'Badge Position', 'password-protected' ),
			array( $this, 'reCAPTCHA_badge_position' ),
			$this->tab,
			$this->options_group
		);

		// reCAPTCHA v2 theme
		add_settings_field(
			'password_protected_recaptcha_theme',
			__( 'Theme', 'password-protected' ),
			array( $this, 'reCAPTCHA_theme' ),
			$this->tab,
			$this->options_group
		);

		// register settings in an array group.
		register_setting( 'password-protected-advanced', $this->options_name, array( 'type' => 'array' ) );
	}

	/**
	 * reCAPTCHA Screen
	 *
	 * @since  2.6
	 *
	 * @return  void  password protected reCAPTCHA settings
	 */
	public static function recpatcha_screen() {
		do_settings_sections( 'password-protected&tab=advanced' );
		submit_button();
	}

	/**
	 * reCAPTCHA Section
	 *
	 * @return  void  password protected reCAPTCHA section
	 */
	public function reCAPTCHA_section() {
		return 1;
	}

	/**
	 * ENable reCAPTCHA
	 *
	 * @since  2.6
	 *
	 * @return  void  password protected reCAPTCHA status field
	 */
	public function reCAPTCHA_enable() {
        echo '<div class="pp-toggle-wrapper">
            <input 
                name="' . esc_attr( $this->options_name ) . '[enable]" 
                id="pp_enable_recaptcha" 
                type="checkbox" 
                value="1" ' . checked( 1, @$this->settings['enable'], false ) . ' 
            />
            <label class="pp-toggle" for="pp_enable_recaptcha">
                <span class="pp-toggle-slider"></span>
            </label>
        </div>
        <label for="pp_enable_recaptcha">
                 ' .
				__( 'Enabled', 'password-protected' ) . '
        </label>';
	}

	/**
	 * reCAPTCHA Version
	 *
	 * @since  2.6
	 *
	 * @return  void  password protected reCAPTCHA version field
	 */
	public function reCAPTCHA_setting() {
		echo '<label>
                <input 
                    ' . checked( 'google_recaptcha_v2', $this->settings['version'], false ) . '
                    type="radio" 
                    name="' . esc_attr( $this->options_name ) . '[version]" 
                    value="google_recaptcha_v2"
                />
                <span>Google reCAPTCHA Version 2</span>
            </label>
            <br />
            <label>
                <input 
                    ' . checked( 'google_recaptcha_v3', $this->settings['version'], false ) . '
                    type="radio" 
                    name="' . esc_attr( $this->options_name ) . '[version]" 
                    value="google_recaptcha_v3"
                />
                <span>Google reCAPTCHA Version 3</span>
            </label>';
	}

	/**
	 * reCAPTCHA Site Key
	 *
	 * @since  2.6
	 *
	 * @return  void  password protected v2/v3 sitekey field
	 */
	public function reCAPTCHA_site_key() {

		echo '<div>
                <input 
                    name="' . esc_attr( $this->options_name ) . '[v2_site_key]" 
                    type="text" 
                    id="pp_google_recaptcha_v2_site_key" 
                    value="' . esc_attr( $this->settings['v2_site_key'] ) . '" 
                    class="regular-text"
                />
                <p class="description">
                    Enter Google reCAPTCHA v2 Site Key.&nbsp;
                    <a target="_blank" href="https://developers.google.com/recaptcha/intro#recaptcha-overview">
                        Click Here
                    </a>
                </p>
            </div>';

			echo '<div>
                    <input 
                        name="' . esc_attr( $this->options_name ) . '[v3_site_key]" 
                        type="text" 
                        id="pp_google_recaptcha_v3_site_key" 
                        value="' . esc_attr( $this->settings['v3_site_key'] ) . '" 
                        class="regular-text"
                    />
                <p class="description">
                    Enter Google reCAPTCHA v3 Site Key.&nbsp;
                    <a target="_blank" href="https://developers.google.com/recaptcha/intro#recaptcha-overview">
                        Click Here
                    </a>
                </p>
            </div>';
	}

	/**
	 * reCAPTCHA Secret Key
	 *
	 * @since  2.6
	 *
	 * @return  void  password protected v2/v3 secretkey field
	 */
	public function reCAPTCHA_secret_key() {

		echo '<div>
                <input 
                    name="' . esc_attr( $this->options_name ) . '[v2_secret_key]" 
                    type="text" 
                    id="pp_google_recaptcha_v2_secret_key" 
                    value="' . esc_attr( $this->settings['v2_secret_key'] ) . '" 
                    class="regular-text" 
                />
                <p class="description">
                    Enter Google reCAPTCHA v2 Secret Key.&nbsp;
                    <a target="_blank" href="https://developers.google.com/recaptcha/intro#recaptcha-overview">
                        Click Here
                    </a>
                </p>
            </div>';
		echo '<div>
                <input 
                    name="' . esc_attr( $this->options_name ) . '[v3_secret_key]" 
                    type="text" 
                    id="pp_google_recaptcha_v3_secret_key" 
                    value="' . esc_attr( $this->settings['v3_secret_key'] ) . '" 
                    class="regular-text" 
                />
                <p class="description">
                    Enter Google reCAPTCHA v3 Secret Key.&nbsp;
                    <a target="_blank" href="https://developers.google.com/recaptcha/intro#recaptcha-overview">
                        Click Here
                    </a>
                </p>
            </div>';
	}

	/**
	 * reCAPTCHA V3 Score
	 *
	 * @since  2.6
	 *
	 * @return  void  password protected v3 score field
	 */
	public function reCAPTCHA_score() {
		echo '<fieldset id="pp_google_recpatcha_v3_score">
                <label>
                    <input 
                        type="radio" 
                        name="' . esc_attr( $this->options_name ) . '[v3_score]" 
                        value="0.1"
                        ' . checked( 0.1, $this->settings['v3_score'], false ) . '
                    />
                    <span>0.1</span>
                </label>
                &nbsp;
                <label>
                    <input 
                        type="radio" 
                        name="' . esc_attr( $this->options_name ) . '[v3_score]" 
                        value="0.2"
                        ' . checked( 0.2, $this->settings['v3_score'], false ) . '
                    />
                    <span>0.2</span>
                </label>
                &nbsp;
                <label>
                    <input
                        type="radio" 
                        name="' . esc_attr( $this->options_name ) . '[v3_score]" 
                        value="0.3"
                        ' . checked( 0.3, $this->settings['v3_score'], false ) . '
                    />
                    <span>0.3</span>
                </label>
                &nbsp;
                <label>
                    <input 
                        type="radio" 
                        name="' . esc_attr( $this->options_name ) . '[v3_score]" 
                        value="0.4"
                        ' . checked( 0.4, $this->settings['v3_score'], false ) . '
                    />
                    <span>0.4</span>
                </label>
                &nbsp;
                <label>
                    <input 
                        type="radio" 
                        name="' . esc_attr( $this->options_name ) . '[v3_score]" 
                        value="0.5"
                        ' . checked( 0.5, $this->settings['v3_score'], false ) . '
                    />
                    <span>0.5</span>
                </label>
                &nbsp;
                <p class="description">Select Google Version 3 Score.</p>
            </fieldset>';
	}

	/**
	 * reCAPTCHA V3 Badge Position
	 *
	 * @since  2.6
	 *
	 * @return  void  password protected v3 badgeposition field
	 */
	public function reCAPTCHA_badge_position() {
		echo '<fieldset id="pp_google_recpatcha_v3_badge">
                    <label>
                        <input 
                            type="radio" 
                            name="' . esc_attr( $this->options_name ) . '[v3_badge]" 
                            value="inline"
                            ' . checked( 'inline', $this->settings['v3_badge'], false ) . '
                        />
                        <span>Inline</span>
                    </label>
                    &nbsp;&nbsp;							
                    <label>
                        <input 
                            type="radio" 
                            name="' . esc_attr( $this->options_name ) . '[v3_badge]" 
                            value="bottomleft"
                            ' . checked( 'bottomleft', $this->settings['v3_badge'], false ) . '
                        />
                        <span>Bottom - Left</span>
                    </label>
                    &nbsp;&nbsp;							
                    <label>
                        <input 
                            type="radio" 
                            name="' . esc_attr( $this->options_name ) . '[v3_badge]" 
                            value="bottomright"
                            ' . checked( 'bottomright', $this->settings['v3_badge'], false ) . '
                        />
                        <span>Bottom - Right</span>
                </label>
            </fieldset>';
	}

	/**
	 * reCAPTCHA V2 Theme
	 *
	 * @since  2.6
	 *
	 * @return  void  password protected v2 theme field
	 */
	public function reCAPTCHA_theme() {
		echo '<label>
                <input 
                    checked="checked" 
                    type="radio" 
                    name="' . esc_attr( $this->options_name ) . '[v2_theme]" 
                    value="light" 
                    ' . checked( 'light', $this->settings['v2_theme'], false ) . '
                />
                <span>Light</span>
            </label>
            <br />
            <label>
                <input 
                    type="radio" 
                    name="' . esc_attr( $this->options_name ) . '[v2_theme]" 
                    value="dark" 
                    ' . checked( 'dark', $this->settings['v2_theme'], false ) . '
                />
                <span>Dark</span>
            </label>
            <p class="description">Select Google reCAPTCHA Version 2 Theme.</p>';
	}

	/**
	 * Add reCAPTCHA on Password Protected Form
	 *
	 * @since  2.6
	 *
	 * @return  void  password protected reCAPTCHA v2 OR v3
	 */
	public function add_recaptcha() {
		if ( ! @$this->settings['enable'] ) {
			return; // recpatcha is disabled
		}

		if ( $this->settings['version'] === 'google_recaptcha_v2' ) {
			$this->display_recaptcha_v2();
		}

		if ( $this->settings['version'] === 'google_recaptcha_v3' ) {
			$this->display_recaptcha_v3();
		}
	}

	/**
	 * Diaplay reCAPTCHA V2
	 *
	 * @since  2.6
	 *
	 * @return  void  password protected reCAPTCHA v2 field
	 */
	public function display_recaptcha_v2() {
        global $Password_Protected;
		wp_enqueue_style( 'pp-recaptcha-style', plugin_dir_url( __DIR__ ) . 'assets/css/recaptcha.css', array(), $Password_Protected->version );
		wp_enqueue_script( 'pp-recaptcha-api-v2', esc_url( 'https://www.google.com/recaptcha/api.js' ), array(), null );
		echo '<div 
                class="g-recaptcha" 
                data-sitekey="' . esc_attr( $this->settings['v2_site_key'] ) . '" 
                data-theme="' . esc_attr( $this->settings['v2_theme'] ) . '"
                >
            </div>';
	}

	/**
	 * Diaplay reCAPTCHA V3
	 *
	 * @since  2.6
	 *
	 * @return  void  password protected reCAPTCHA v3 field
	 */
	public function display_recaptcha_v3() {
		$grecaptcha_v3_site_key = isset( $this->settings['v3_site_key'] ) ? esc_attr( $this->settings['v3_site_key'] ) : '';
		$grecaptcha_v3_badge    = isset( $this->settings['v3_badge'] ) ? esc_attr( $this->settings['v3_badge'] ) : 'bottomright';

		$script = <<<EOT
            if('function' !== typeof pprecaptcha) {
                function pprecaptcha() {
                    grecaptcha.ready(function() {
                        [].forEach.call(document.querySelectorAll('.pp-g-recaptcha'), function(el) {
                            const action = el.getAttribute('data-action');
                            const form = el.form;
                            form.addEventListener('submit', function(e) {
                                e.preventDefault();
                                grecaptcha.execute('$grecaptcha_v3_site_key', {action: action}).then(function(token) {
                                    el.setAttribute('value', token);
                                    const button = form.querySelector('[type="submit"]');
                                    if(button) {
                                        const input = document.createElement('input');
                                        input.type = 'hidden';
                                        input.name = button.getAttribute('name');
                                        input.value = button.value;
                                        input.classList.add('pp-submit-input');
                                        var inputEls = document.querySelectorAll('.pp-submit-input');
                                        [].forEach.call(inputEls, function(inputEl) {
                                            inputEl.remove();
                                        });
                                        form.appendChild(input);
                                    }
                                    HTMLFormElement.prototype.submit.call(form);
                                });
                            });
                        });
                    });
                }
            }
EOT;

		wp_enqueue_script( 'recaptcha-api-v3', ( 'https://www.google.com/recaptcha/api.js?onload=pprecaptcha&render=' . esc_attr( $grecaptcha_v3_site_key ) . '&badge=' . esc_attr( $grecaptcha_v3_badge ) ), array(), null );
		wp_add_inline_script( 'recaptcha-api-v3', $script ); ?>
		<input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-password_protected" class="pp-g-recaptcha" data-action="password_protected">
		<?php
	}

	/**
	 * Verify reCAPTCHA
	 * process reCAPTCHA
	 *
	 * @since  2.6
	 *
	 * @return  object  WP_Error
	 */
	public function verify_recaptcha( $errors ) {

		if ( ! @$this->settings['enable'] ) {
			return $errors; // return errors
		}

		if ( $this->settings['version'] === 'google_recaptcha_v2' ) {

			$grecaptcha_v2_site_key   = isset( $this->settings['v2_site_key'] ) ? esc_attr( $this->settings['v2_site_key'] ) : '';
			$grecaptcha_v2_secret_key = isset( $this->settings['v2_secret_key'] ) ? esc_attr( $this->settings['v2_secret_key'] ) : '';

			if ( empty( $grecaptcha_v2_site_key ) || empty( $grecaptcha_v2_secret_key ) ) {
				$errors->add( 001, 'Google reCaptcha keys not found.' );
			}

			if ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) {
				$response = wp_remote_post(
					'https://www.google.com/recaptcha/api/siteverify',
					array(
						'body' => array(
							'secret'   => $grecaptcha_v2_secret_key,
							'response' => sanitize_text_field( $_POST['g-recaptcha-response'] ),
						),
					)
				);

				$data = wp_remote_retrieve_body( $response );
				$data = json_decode( $data );

				if ( isset( $data->{'error-codes'} ) && is_array( $data->{'error-codes'} ) && count( $data->{'error-codes'} ) ) {
					foreach ( $data->{'error-codes'} as $index => $error_code ) {
						$errors->add( $index, $error_code );
					}
				}

				if ( isset( $data->success ) && true === $data->success ) {
					return $errors;
				}
			}
			$error_message = wp_kses( __( '<strong>ERROR:</strong> Please confirm you are not a robot.', 'password-protected' ), array( 'strong' => array() ) );
			$errors->add( 'captcha_invalid', $error_message );

			return $errors;

		} elseif ( $this->settings['version'] === 'google_recaptcha_v3' ) {

			$grecaptcha_v3_site_key   = isset( $this->settings['v3_site_key'] ) ? esc_attr( $this->settings['v3_site_key'] ) : '';
			$grecaptcha_v3_secret_key = isset( $this->settings['v3_secret_key'] ) ? esc_attr( $this->settings['v3_secret_key'] ) : '';
			$grecaptcha_v3_score      = isset( $this->settings['v3_score'] ) ? esc_attr( $this->settings['v3_score'] ) : '0.3';

			if ( empty( $grecaptcha_v3_site_key ) || empty( $grecaptcha_v3_secret_key ) ) {
				$errors->add( 001, 'Google reCaptcha keys not found.' );
			}

			if ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) {
				$response = wp_remote_post(
					'https://www.google.com/recaptcha/api/siteverify',
					array(
						'body' => array(
							'secret'   => $grecaptcha_v3_secret_key,
							'response' => sanitize_text_field( $_POST['g-recaptcha-response'] ),
							'remoteip' => self::get_ip_address(),
						),
					)
				);

				$data = wp_remote_retrieve_body( $response );
				$data = json_decode( $data );

				if ( isset( $data->{'error-codes'} ) && is_array( $data->{'error-codes'} ) && count( $data->{'error-codes'} ) ) {
					foreach ( $data->{'error-codes'} as $index => $error_code ) {
						$errors->add( $index, $error_code );
					}
				}

				if ( isset( $data->success ) && true === $data->success ) {
					$grecaptcha_v3_score = (float) $grecaptcha_v3_score;
					if ( isset( $data->action ) && ( 'password_protected' === $data->action ) && isset( $data->score ) && $data->score >= $grecaptcha_v3_score ) {
						return $errors;
					} else {
						$error_message = wp_kses( __( '<strong>ERROR:</strong> Low Score ', 'password-protected' ) . ':' . esc_html( $data->score ), array( 'strong' => array() ) );
						$errors->add( 002, $error_message );
					}
				}
			}

			return $errors;
		}

	}

	/**
	 * Get IP Address
	 *
	 * @since  2.6
	 *
	 * @return  string  client IP address
	 */
	private static function get_ip_address() {
		$ipaddress = '';
		if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ipaddress = sanitize_text_field( $_SERVER['HTTP_CLIENT_IP'] );
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ipaddress = sanitize_text_field( $_SERVER['HTTP_X_FORWARDED_FOR'] );
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED'] ) ) {
			$ipaddress = sanitize_text_field( $_SERVER['HTTP_X_FORWARDED'] );
		} elseif ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
			$ipaddress = sanitize_text_field( $_SERVER['HTTP_FORWARDED_FOR'] );
		} elseif ( isset( $_SERVER['HTTP_FORWARDED'] ) ) {
			$ipaddress = sanitize_text_field( $_SERVER['HTTP_FORWARDED'] );
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$ipaddress = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
		} else {
			$ipaddress = 'UNKNOWN';
		}
		return $ipaddress;
	}

}
