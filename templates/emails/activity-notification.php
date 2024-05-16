<?php
/**
 * Email Template for Activity report
 *
 * @package Password Protected Pro
 */

defined( 'ABSPATH' ) || exit;

$has_pro   = class_exists( 'Password_Protected_pro' );
$image_url = PASSWORD_PROTECTED_URL . 'assets/images/';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php bloginfo( 'name' ) ?></title>
    <style id="admin-email-notification--text-css" type="text/css">
        * {
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>
<table style="width: 100%;height: 100vh;background: #000;">
    <tr>
        <td style="">
            <table style="width: 471px;height: 557px;margin: 0 auto;padding: 20px;background: #fff;">
                <tr>
                    <td style="vertical-align: top;width: 100%;">
                        <div style="text-align: center;">
                            <img src="<?php echo esc_url( PASSWORD_PROTECTED_URL ) ?>assets/images/cropped-logo.png" alt="">
                        </div>
                        <div style="width: 424px;height: 60px;line-height: 15px;">
                            <p style="font-size: 12px;font-weight: 400;font-family: Inter,serif;line-height: 25px">
								<?php
								printf( __( 'Hi %1$s,', 'password-protected' ), '<b>' . get_option( 'admin_email' ) . '</b>' );
								?>
                            </p>
                            <p style="font-size: 12px;font-weight: 400;font-family: Inter,serif;line-height: 25px">
								<?php
								printf(
									__( 'Here is a quick overview of who is accessing your site %1$s to %2$s', 'password-protected' ),
									'<b>' . esc_attr( gmdate( 'd-M', $time[0] ) ). '</b>',
									'<b>' . esc_attr( gmdate( 'd-M', $time[1] ) ) . '</b>',
								);
								?>
                            </p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: middle;width: 100%;">
                        <div style="width: 336px;margin: 0 auto;">

                            <div style="padding: 5px 0;margin:0 5px;width: 100px;height: 120px;float: left;border: 1px solid #BEB8E4;background: #ECE8F9;border-radius: 5px;">
                                <table style="width: 100%;height: 100%;">
                                    <tr>
                                        <td style="vertical-align: top">
                                            <div style="width: 38px;height: 38px;border-radius: 50px;background: #BEB8E4; margin: auto">
                                                <div style="line-height: 38px;text-align:center;">
                                                    <img src="<?php echo esc_url( $image_url ); ?>total-attempts.png" />
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-family: Inter,serif;font-weight: 400;font-size: 12px;line-height: 14px;text-align:center;color: #151D48; vertical-align: middle;">
                                            <p><?php esc_html_e( 'Total', 'password-protected' ); ?></p>
                                            <p><?php esc_html_e( 'Attempts', 'password-protected' ); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td >
                                            <h3 style="font-family: Inter,serif;font-weight: 700;font-size: 24px;line-height: 29px;color: #151D48;vertical-align: bottom;text-align:center;"><?php echo esc_html( $total ); ?></h3>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div style="padding: 5px 0;margin:0 5px;width: 100px;height: 120px;float: left;border: 1px solid #26E2AA;background: #EAFFF6;border-radius: 5px;">
                                <table style="width: 100%;height: 100%;">
                                    <tr>
                                        <td style="vertical-align: top;">
                                            <div style="width: 38px;height: 38px;border-radius: 50px;background: #26e2c0; margin: auto">
                                                <div style="line-height: 38px;text-align:center;">
                                                    <img src="<?php echo esc_url( $image_url ) ?>success.png" alt="">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-family: Inter,serif;font-weight: 400;font-size: 12px;line-height: 14px;text-align:center;color: #151D48; vertical-align: middle;">
                                            <p><?php esc_html_e( 'Successful', 'password-protected' ); ?></p>
                                            <p><?php esc_html_e( 'Attempts', 'password-protected' ); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td >
                                            <h3 style="font-family: Inter,serif;font-weight: 700;font-size: 24px;line-height: 29px;color: #151D48;vertical-align: bottom;text-align:center;"><?php echo esc_html( $success ); ?></h3>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div style="padding: 5px 0;margin:0 5px;width: 100px;height: 120px;float: left;border: 1px solid #E4B8D2;background: #F9E8F0;border-radius: 5px;">
                                <table style="width: 100%;height: 100%;">
                                    <tr>
                                        <td style="vertical-align: top">
                                            <div style="width: 38px;height: 38px;border-radius: 50px;background: #e4b8d0; margin: auto">
                                                <div style="line-height: 38px;text-align:center;">
                                                    <img src="<?php echo esc_url( $image_url ); ?>failed.png" alt="">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-family: Inter,serif;font-weight: 400;font-size: 12px;line-height: 14px;text-align:center;color: #151D48; vertical-align: middle;">
                                            <p><?php esc_html_e( 'Failed', 'password-protected' ) ?></p>
                                            <p><?php esc_html_e( 'Attempts', 'password-protected' ) ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td >
                                            <h3 style="font-family: Inter,serif;font-weight: 700;font-size: 24px;line-height: 29px;color: #151D48;vertical-align: bottom;text-align:center;"><?php echo esc_html( $failed ); ?></h3>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div style="clear:both;display:table;"></div>
                        </div>

                        <?php if ( ! $has_pro ) : ?>
                            <div style="margin: 20px 0">
                                <div style="width: 424px;height: 257px;border-radius: 10px;border: 1px solid #CDCDCD;overflow: hidden">
                                    <div style="width: 100%;height: 33px;background: #F9AA39;">
                                        <p style="font-family: Inter,serif;font-weight: 600;line-height: 32px;font-size: 14px;color: #fff;padding: 0 15px">
                                            <img src="<?php esc_url( $image_url ); ?>chart.png" alt="">
					                        <?php esc_html_e( 'Want more insights about your visitors?', 'password-protected' ); ?>
                                        </p>
                                    </div>
                                    <div style="border-bottom: 1px solid #CDCDCD;">
                                        <p style="text-align:center;font-family: Inter,serif;font-weight: 700;font-size: 13px;line-height: 16px;color: #8076FF;padding:5px 0;">
					                        <?php esc_html_e( 'Get the Password Protected PRO and see,', 'password-protected' ); ?>
                                        </p>
                                    </div>

                                    <div style="padding: 5px 15px;">
                                        <div style="display: inline-block;">
                                            <img src="<?php echo esc_url( $image_url ); ?>check.png" style="margin-bottom: -1px;" alt="">
                                        </div>
                                        <div style="display: inline-block;font-family: Inter,serif;font-weight: 400;font-size: 11px;line-height: 20px;">
					                        <?php esc_html_e( 'Protect Specific Post Types', 'password-protected' ); ?>
                                        </div>
                                    </div>
                                    <div style="padding: 5px 15px;border-top: 1px solid #CDCDCD;border-bottom: 1px solid #CDCDCD;">
                                        <div style="display: inline-block;">
                                            <img src="<?php echo esc_url( $image_url ); ?>check.png" style="margin-bottom: -1px;" alt="">
                                        </div>
                                        <div style="display: inline-block;font-family: Inter,serif;font-weight: 400;font-size: 11px;line-height: 20px;">
					                        <?php esc_html_e( 'Protect Specific Categories / Taxonomies', 'password-protected' ); ?>
                                        </div>
                                    </div>
                                    <div style="padding: 5px 15px;">
                                        <div style="display: inline-block;">
                                            <img src="<?php echo esc_url( $image_url ); ?>check.png" style="margin-bottom: -1px;" alt="">
                                        </div>
                                        <div style="display: inline-block;font-family: Inter,serif;font-weight: 400;font-size: 11px;line-height: 20px;">
					                        <?php esc_html_e( 'Display Activity Log for Each Password Attempt', 'password-protected' ); ?>
                                        </div>
                                    </div>
                                    <div style="padding: 5px 15px;border-top: 1px solid #CDCDCD;border-bottom: 1px solid #CDCDCD;">
                                        <div style="display: inline-block;">
                                            <img src="<?php echo esc_url( $image_url ); ?>check.png" style="margin-bottom: -1px;" alt="">
                                        </div>
                                        <div style="display: inline-block;font-family: Inter,serif;font-weight: 400;font-size: 11px;line-height: 20px;">
			                                <?php esc_html_e( 'Set a Password Expiration Date and Usage Limit', 'password-protected' ); ?>
                                        </div>
                                    </div>
                                    <div style="padding: 5px 15px;">
                                        <div style="display: inline-block;">
                                            <img src="<?php echo esc_url( $image_url ); ?>check.png" style="margin-bottom: -1px;" alt="">
                                        </div>
                                        <div style="display: inline-block;font-family: Inter,serif;font-weight: 400;font-size: 11px;line-height: 20px;">
			                                <?php esc_html_e( 'Get a Bypass URL to Access your WordPress Site without a Password', 'password-protected' ); ?>
                                        </div>
                                    </div>

                                    <div style="border-top: 1px solid #CDCDCD;">
                                        <div style="padding: 10px;">
                                            <a href="https://passwordprotectedwp.com/pricing/?utm_source=plugin&utm_medium=email_template" style="display: block;width: 120px;height: 22px;background-color:#F9AA39;font-weight: 500;font-size: 10px;font-family: Inter,serif;border-radius: 10px;color: #fff;text-decoration: none;">
											<span style="line-height: 20px;padding: 0 5px 0 10px;display: inline-block;">
                                                <?php esc_html_e( 'Upgrade to PRO', 'password-protected' ); ?>
                                            </span>
                                                <span style="line-height: 20px;display: inline-block;">&gt;</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                            <?php do_action( 'password_protected_pro_email_notification_template', 'items_table' ); ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: bottom;width: 100%;">
                        <p style="font-family: Inter,serif;font-size: 12px;line-height: 15px;font-weight: 500;color: #8076FF;text-align:center;">
                            <?php
                                printf(
                                    __( 'This email was autogenerated and sent from %1$s.', 'password-protected' ),
                                    '<a href="' . esc_url( site_url() ) . '">' . esc_attr( get_bloginfo( 'name' ) ) . '</a>'
                                );
                            ?>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>