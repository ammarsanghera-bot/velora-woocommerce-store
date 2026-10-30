<?php
/**
 * Velora theme bootstrap.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

define( 'VELORA_VERSION', '1.0.0' );
define( 'VELORA_DIR', get_template_directory() );
define( 'VELORA_URI', get_template_directory_uri() );

require_once VELORA_DIR . '/inc/setup.php';
require_once VELORA_DIR . '/inc/enqueue.php';
require_once VELORA_DIR . '/inc/template-functions.php';
require_once VELORA_DIR . '/inc/woocommerce.php';
require_once VELORA_DIR . '/inc/theme-options.php';
require_once VELORA_DIR . '/inc/seo.php';
require_once VELORA_DIR . '/inc/performance.php';

/**
 * Notice when WooCommerce or Velora Core is missing.
 *
 * @return void
 */
function velora_dependency_notices() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	if ( ! class_exists( 'WooCommerce' ) ) {
		echo '<div class="notice notice-error"><p>' . esc_html__( 'Velora requires WooCommerce.', 'velora' ) . '</p></div>';
	}
	if ( ! defined( 'VELORA_CORE_VERSION' ) ) {
		echo '<div class="notice notice-warning"><p>' . esc_html__( 'Activate Velora Core for wishlist, product fields, and demo import.', 'velora' ) . '</p></div>';
	}
}
add_action( 'admin_notices', 'velora_dependency_notices' );
