<?php
/**
 * Basic wishlist for guests (cookie) and logged-in users (user meta).
 *
 * @package Velora_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Cookie name.
 *
 * @return string
 */
function velora_core_wishlist_cookie() {
	return 'velora_wishlist';
}

/**
 * Get wishlist product IDs.
 *
 * @return int[]
 */
function velora_core_get_wishlist_ids() {
	if ( is_user_logged_in() ) {
		$ids = get_user_meta( get_current_user_id(), '_velora_wishlist', true );
		return is_array( $ids ) ? array_map( 'absint', $ids ) : array();
	}

	if ( empty( $_COOKIE[ velora_core_wishlist_cookie() ] ) ) {
		return array();
	}

	$raw = sanitize_text_field( wp_unslash( $_COOKIE[ velora_core_wishlist_cookie() ] ) );
	$ids = array_filter( array_map( 'absint', explode( ',', $raw ) ) );
	return array_values( array_unique( $ids ) );
}

/**
 * Persist wishlist IDs.
 *
 * @param int[] $ids Product IDs.
 * @return void
 */
function velora_core_save_wishlist_ids( array $ids ) {
	$ids = array_values( array_unique( array_filter( array_map( 'absint', $ids ) ) ) );

	if ( is_user_logged_in() ) {
		update_user_meta( get_current_user_id(), '_velora_wishlist', $ids );
		return;
	}

	$secure = is_ssl();
	setcookie( velora_core_wishlist_cookie(), implode( ',', $ids ), time() + MONTH_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure, true );
	$_COOKIE[ velora_core_wishlist_cookie() ] = implode( ',', $ids );
}

/**
 * Wishlist shortcode output.
 *
 * @return string
 */
function velora_core_wishlist_shortcode() {
	$ids = velora_core_get_wishlist_ids();
	ob_start();
	echo '<div class="velora-wishlist">';
	if ( empty( $ids ) ) {
		echo '<p class="velora-empty">' . esc_html__( 'Your wishlist is empty.', 'velora-core' ) . '</p>';
	} else {
		echo '<ul class="products columns-3">';
		foreach ( $ids as $product_id ) {
			$product = wc_get_product( $product_id );
			if ( ! $product ) {
				continue;
			}
			$post_object = get_post( $product_id );
			setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			wc_get_template_part( 'content', 'product' );
		}
		wp_reset_postdata();
		echo '</ul>';
	}
	echo '</div>';
	return ob_get_clean();
}
add_shortcode( 'velora_wishlist', 'velora_core_wishlist_shortcode' );
