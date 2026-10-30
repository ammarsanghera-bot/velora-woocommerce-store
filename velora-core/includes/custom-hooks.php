<?php
/**
 * Store-specific WooCommerce hooks.
 *
 * @package Velora_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Show custom product meta under single product summary.
 *
 * @return void
 */
function velora_core_single_product_extras() {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$id        = $product->get_id();
	$subtitle  = velora_core_get_product_meta( $id, 'subtitle' );
	$material  = velora_core_get_product_meta( $id, 'material' );
	$highlight = velora_core_get_product_meta( $id, 'highlight' );
	$dispatch  = velora_core_get_product_meta( $id, 'dispatch' );
	$care      = velora_core_get_product_meta( $id, 'care' );

	if ( $subtitle ) {
		echo '<p class="velora-product-subtitle">' . esc_html( $subtitle ) . '</p>';
	}

	echo '<div class="velora-product-extras">';
	if ( $highlight ) {
		echo '<p><strong>' . esc_html__( 'Highlight', 'velora-core' ) . ':</strong> ' . esc_html( $highlight ) . '</p>';
	}
	if ( $material ) {
		echo '<p><strong>' . esc_html__( 'Material', 'velora-core' ) . ':</strong> ' . esc_html( $material ) . '</p>';
	}
	if ( $dispatch ) {
		echo '<p><strong>' . esc_html__( 'Dispatch', 'velora-core' ) . ':</strong> ' . esc_html( $dispatch ) . '</p>';
	}
	if ( $care ) {
		echo '<p><strong>' . esc_html__( 'Care', 'velora-core' ) . ':</strong> ' . esc_html( $care ) . '</p>';
	}
	echo '</div>';

	$in_wishlist = in_array( $id, velora_core_get_wishlist_ids(), true );
	printf(
		'<button type="button" class="velora-wishlist-btn button" data-product-id="%1$d" aria-pressed="%2$s">%3$s</button>',
		absint( $id ),
		$in_wishlist ? 'true' : 'false',
		esc_html( $in_wishlist ? __( 'Remove from Wishlist', 'velora-core' ) : __( 'Add to Wishlist', 'velora-core' ) )
	);
}
add_action( 'woocommerce_single_product_summary', 'velora_core_single_product_extras', 6 );

/**
 * Log order status changes for debugging demo stores (no sensitive data).
 *
 * @param int    $order_id   Order ID.
 * @param string $old_status Old status.
 * @param string $new_status New status.
 * @return void
 */
function velora_core_order_status_changed( $order_id, $old_status, $new_status ) {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		error_log( sprintf( 'Velora order #%d status: %s → %s', absint( $order_id ), sanitize_key( $old_status ), sanitize_key( $new_status ) ) );
	}
}
add_action( 'woocommerce_order_status_changed', 'velora_core_order_status_changed', 10, 3 );

/**
 * Shipping & returns note after add to cart button.
 *
 * @return void
 */
function velora_core_after_add_to_cart_note() {
	echo '<p class="velora-shipping-note">' . esc_html__( 'Free returns within 14 days on eligible items. Shipping calculated at checkout.', 'velora-core' ) . '</p>';
}
add_action( 'woocommerce_after_add_to_cart_button', 'velora_core_after_add_to_cart_note' );
