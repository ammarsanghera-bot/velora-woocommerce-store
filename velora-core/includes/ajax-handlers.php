<?php
/**
 * AJAX handlers for wishlist and newsletter stub.
 *
 * @package Velora_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Toggle wishlist product.
 *
 * @return void
 */
function velora_core_ajax_toggle_wishlist() {
	velora_core_verify_ajax();

	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
	if ( ! $product_id || 'product' !== get_post_type( $product_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid product.', 'velora-core' ) ), 400 );
	}

	$ids    = velora_core_get_wishlist_ids();
	$action = 'added';

	if ( in_array( $product_id, $ids, true ) ) {
		$ids    = array_values( array_diff( $ids, array( $product_id ) ) );
		$action = 'removed';
	} else {
		$ids[] = $product_id;
	}

	velora_core_save_wishlist_ids( $ids );

	wp_send_json_success(
		array(
			'action' => $action,
			'count'  => count( $ids ),
			'ids'    => $ids,
		)
	);
}
add_action( 'wp_ajax_velora_toggle_wishlist', 'velora_core_ajax_toggle_wishlist' );
add_action( 'wp_ajax_nopriv_velora_toggle_wishlist', 'velora_core_ajax_toggle_wishlist' );

/**
 * Newsletter signup stub — validates email only; no provider connected by default.
 *
 * @return void
 */
function velora_core_ajax_newsletter() {
	velora_core_verify_ajax();

	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Enter a valid email address.', 'velora-core' ) ), 400 );
	}

	/**
	 * Hook for connecting a real email provider (Mailchimp, etc.).
	 *
	 * @param string $email Subscriber email.
	 */
	do_action( 'velora_core_newsletter_subscribe', $email );

	wp_send_json_success(
		array(
			'message' => __( 'Thanks — this is a demo signup confirmation. Connect an email provider to store subscribers.', 'velora-core' ),
		)
	);
}
add_action( 'wp_ajax_velora_newsletter', 'velora_core_ajax_newsletter' );
add_action( 'wp_ajax_nopriv_velora_newsletter', 'velora_core_ajax_newsletter' );
