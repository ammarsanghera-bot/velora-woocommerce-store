<?php
/**
 * Product badges (sale / new / featured).
 *
 * @package Velora_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render product badge HTML.
 *
 * @return void
 */
function velora_core_product_badge() {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$badges = array();
	if ( $product->is_on_sale() ) {
		$badges[] = __( 'Sale', 'velora-core' );
	}
	if ( $product->is_featured() ) {
		$badges[] = __( 'Featured', 'velora-core' );
	}
	$created = strtotime( $product->get_date_created() ? $product->get_date_created()->date( 'Y-m-d H:i:s' ) : 'now' );
	if ( $created && $created > strtotime( '-30 days' ) ) {
		$badges[] = __( 'New', 'velora-core' );
	}

	if ( empty( $badges ) ) {
		return;
	}

	echo '<span class="velora-badge">' . esc_html( $badges[0] ) . '</span>';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'velora_core_product_badge', 9 );
