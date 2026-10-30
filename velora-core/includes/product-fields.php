<?php
/**
 * Custom WooCommerce product fields (does not duplicate native WC fields).
 *
 * @package Velora_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add custom fields to the General product tab.
 *
 * @return void
 */
function velora_core_product_fields() {
	echo '<div class="options_group">';
	woocommerce_wp_text_input(
		array(
			'id'          => '_velora_subtitle',
			'label'       => __( 'Product subtitle', 'velora-core' ),
			'desc_tip'    => true,
			'description' => __( 'Optional short line under the product title.', 'velora-core' ),
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'    => '_velora_material',
			'label' => __( 'Material', 'velora-core' ),
		)
	);
	woocommerce_wp_textarea_input(
		array(
			'id'    => '_velora_care',
			'label' => __( 'Care instructions', 'velora-core' ),
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'    => '_velora_highlight',
			'label' => __( 'Product highlight', 'velora-core' ),
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'          => '_velora_dispatch',
			'label'       => __( 'Estimated dispatch time', 'velora-core' ),
			'placeholder' => __( 'e.g. Ships within 2–4 business days', 'velora-core' ),
		)
	);
	echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'velora_core_product_fields' );

/**
 * Save custom product fields.
 *
 * @param int $post_id Product ID.
 * @return void
 */
function velora_core_save_product_fields( $post_id ) {
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		'_velora_subtitle'  => 'sanitize_text_field',
		'_velora_material'  => 'sanitize_text_field',
		'_velora_care'      => 'sanitize_textarea_field',
		'_velora_highlight' => 'sanitize_text_field',
		'_velora_dispatch'  => 'sanitize_text_field',
	);

	foreach ( $fields as $key => $callback ) {
		if ( isset( $_POST[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- WC product save handles nonce.
			update_post_meta( $post_id, $key, call_user_func( $callback, wp_unslash( $_POST[ $key ] ) ) );
		}
	}
}
add_action( 'woocommerce_process_product_meta', 'velora_core_save_product_fields' );

/**
 * Get a Velora product meta value.
 *
 * @param int    $product_id Product ID.
 * @param string $key        Meta key without prefix.
 * @return string
 */
function velora_core_get_product_meta( $product_id, $key ) {
	return (string) get_post_meta( $product_id, '_velora_' . $key, true );
}
