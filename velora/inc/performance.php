<?php
/**
 * Performance helpers.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

/**
 * Disable WooCommerce styles that conflict; we ship custom CSS.
 * Keep scripts required for cart/checkout/variations.
 *
 * @return void
 */
function velora_dequeue_wc_styles() {
	wp_dequeue_style( 'woocommerce-general' );
	wp_dequeue_style( 'woocommerce-layout' );
	wp_dequeue_style( 'woocommerce-smallscreen' );
}
add_action( 'wp_enqueue_scripts', 'velora_dequeue_wc_styles', 100 );
