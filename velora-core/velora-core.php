<?php
/**
 * Plugin Name:       Velora Core
 * Description:       Custom ecommerce features for the Velora WooCommerce theme: product fields, badges, wishlist, and store hooks.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      8.1
 * Author:            Velora
 * License:           GPL-2.0-or-later
 * Text Domain:       velora-core
 *
 * @package Velora_Core
 */

defined( 'ABSPATH' ) || exit;

define( 'VELORA_CORE_VERSION', '1.0.0' );
define( 'VELORA_CORE_FILE', __FILE__ );
define( 'VELORA_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'VELORA_CORE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load includes after plugins are available.
 *
 * @return void
 */
function velora_core_bootstrap() {
	require_once VELORA_CORE_PATH . 'includes/security.php';
	require_once VELORA_CORE_PATH . 'includes/product-fields.php';
	require_once VELORA_CORE_PATH . 'includes/product-badges.php';
	require_once VELORA_CORE_PATH . 'includes/wishlist.php';
	require_once VELORA_CORE_PATH . 'includes/custom-hooks.php';
	require_once VELORA_CORE_PATH . 'includes/ajax-handlers.php';
	require_once VELORA_CORE_PATH . 'includes/admin-settings.php';
	require_once VELORA_CORE_PATH . 'includes/demo-import.php';
}
add_action( 'plugins_loaded', 'velora_core_bootstrap' );

/**
 * Admin notice if WooCommerce is missing.
 *
 * @return void
 */
function velora_core_wc_notice() {
	if ( class_exists( 'WooCommerce' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-error"><p>' . esc_html__( 'Velora Core requires WooCommerce to be installed and active.', 'velora-core' ) . '</p></div>';
}
add_action( 'admin_notices', 'velora_core_wc_notice' );

register_activation_hook(
	__FILE__,
	static function () {
		if ( ! get_option( 'velora_core_wishlist_page' ) ) {
			$page_id = wp_insert_post(
				array(
					'post_title'   => 'Wishlist',
					'post_name'    => 'wishlist',
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_content' => '[velora_wishlist]',
				)
			);
			if ( ! is_wp_error( $page_id ) ) {
				update_option( 'velora_core_wishlist_page', (int) $page_id );
			}
		}
		flush_rewrite_rules();
	}
);

register_deactivation_hook(
	__FILE__,
	static function () {
		flush_rewrite_rules();
	}
);
